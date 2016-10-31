<?php

use Illuminate\Support\Facades\Cache;
use Cookbook\Core\Facades\Trunk;
use Illuminate\Support\Debug\Dumper;

require_once(__DIR__ . '/../database/seeders/EavDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/LocaleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/FileDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/WorkflowDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/UserDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ScopeDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/RoleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClientDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClearDB.php');

class UserTest extends Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		parent::setUp();
		// unset($this->app);
		// call migrations specific to our tests, e.g. to seed the db
		// the path option should be relative to the 'path.database'
		// path unless `--path` option is available.
		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/eav/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/filesystem/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/locales/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/workflows/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/users/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/lucadegasperi/oauth2-server-laravel/database/migrations'),
		]);

		$this->artisan('db:seed', [
			'--class' => 'EavDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'LocaleDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'WorkflowDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'UserDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'ScopeDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'RoleDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'ClientDbSeeder'
		]);

		$this->d = new Dumper();

		$this->server = [
			'HTTP_Authorization' => 'Bearer e4qrk81UaGtbrJKNY3X5qe2vIn4A1cC3jzDeL9zz'
		];

	}

	public function tearDown()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		// parent::tearDown();
		Trunk::forgetAll();
		// $this->artisan('db:seed', [
		// 	'--class' => 'ClearDB'
		// ]);

		DB::disconnect();

		parent::tearDown();
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 *
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'testbench');
		$app['config']->set('database.connections.testbench', [
			'driver'   	=> 'mysql',
			'host'      => '127.0.0.1',
			'port'		=> '33060',
			'database'	=> 'cookbook_testbench',
			'username'  => 'homestead',
			'password'  => 'secret',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		]);

		$app['config']->set('cache.default', 'file');

		$app['config']->set('cache.stores.file', [
			'driver'	=> 'file',
			'path'   	=> realpath(__DIR__ . '/../storage/cache/'),
		]);

		// $config = require(realpath(__DIR__.'/../../config/eav.php'));

		// $app['config']->set(
		// 	'Cookbook::eav', $config
		// );

		// var_dump('CONFIG SETTED');
	}

	protected function getPackageProviders($app)
	{
		return [
			'LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
			'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
			'Dingo\Api\Provider\LaravelServiceProvider',
			'Cookbook\Core\CoreServiceProvider',
			'Cookbook\Locales\LocalesServiceProvider',
			'Cookbook\Eav\EavServiceProvider',
			'Cookbook\Filesystem\FilesystemServiceProvider',
			'Cookbook\Workflows\WorkflowsServiceProvider',
			'Cookbook\OAuth2\OAuth2ServiceProvider',
			'Cookbook\Api\ApiServiceProvider'
		];
	}

	public function testNotAuthorized() {
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/users/1');

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(400);

		$this->seeJson([
			"error" => "invalid_request",
  			"status_code" => 400
		]);
	}

	public function testCreateUser()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'name' => 'John Doe',
			'email' => 'john.doe@email.com',
			'password' => 'secret123',
			'roles' => [
				[
					'id' => 1,
					'type' => 'role'
				]
			]
		];

		$this->refreshApplication();

		$this->post('api/users', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(201);

		$this->seeJson([
			'name' => 'John Doe',
			'email' => 'john.doe@email.com'
		]);

		$this->assertFalse(isset(json_decode($this->response->getContent(), true)['data']['password']));

		$this->seeInDatabase('users', ['name' => 'John Doe', 'email' => 'john.doe@email.com']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'name' => 'John Doe',
			'email' => 'john.doe',
			'password' => 'secret123',
			'roles' => [
				[
					'id' => 1,
					'type' => 'role'
				]
			]
		];

		$this->post('api/users', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(422);

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'user' => [
					'email' => ['The email must be a valid email address.']
				]
			]
		]);
	}

	public function testUpdateUser()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'name' => 'Jane Margaret Doe'
		];

		$this->patch('api/users/1', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			'name' => 'Jane Margaret Doe'
		]);

		$this->seeInDatabase('users', ['id' => 1, 'name' => 'Jane Margaret Doe']);


	}

	public function testChangeUserPassword()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'password' => 'newpassword123'
		];

		$this->post('api/users/1/change-password', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(204);

		$this->seeInDatabase('users', ['id' => 1, 'name' => 'Jane Doe']);


	}

	public function testDeleteUser()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/users/1', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(204);

		$this->dontSeeInDatabase('users', ['id' => 1]);
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/users/123', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);

		$this->seeInDatabase('users', ['id' => 1]);
	}

	public function testFetchUser()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/users/1', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			"id"=> 1,
		    "name"=> "Jane Doe",
		    "email"=> "jane.doe@email.com",
		    "type"=> "user"
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/users/112233', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}


	public function testGetUsers()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/users', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 2, count(json_decode($this->response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/users?sort=-email&limit=3', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 2, count(json_decode($this->response->getContent(), true)['data']) );
	}
}
