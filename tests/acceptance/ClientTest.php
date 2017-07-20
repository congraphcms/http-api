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

class ClientTest extends Orchestra\Testbench\TestCase
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
			'port'		=> '3306',
			'database'	=> 'cookbook_testbench',
			'username'  => 'root',
			'password'  => '',
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

		$this->get('api/clients/1');

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(400);

		$this->seeJson([
			"error" => "invalid_request",
  			"status_code" => 400
		]);
	}

	public function testCreateClient()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'name' => 'Jane\'s Mobile App',
			'scopes' => ['manage_content'],
			'grants' => ['password']
		];

		$this->refreshApplication();

		$this->post('api/clients', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(201);

		$this->seeJson([
			'name' => 'Jane\'s Mobile App'
		]);

		$id = json_decode($this->response->getContent(), true)['data']['id'];
		$secret = json_decode($this->response->getContent(), true)['data']['secret'];

		$this->seeInDatabase('oauth_clients', ['id' => $id, 'secret' => $secret, 'name' => 'Jane\'s Mobile App']);

	}

	public function testUpdateClient()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'name' => 'Jane\'s Web App'
		];

		$this->patch('api/clients/iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			'name' => 'Jane\'s Web App'
		]);

		$this->seeInDatabase('oauth_clients', ['id' => 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR', 'name' => 'Jane\'s Web App']);


	}

	public function testDeleteClient()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/clients/iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(204);

		$this->dontSeeInDatabase('oauth_clients', ['id' => 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR']);
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/clients/123', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);

		$this->seeInDatabase('oauth_clients', ['id' => 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR']);
	}

	public function testFetchClient()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/clients/iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			"id"=> 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR',
			"secret"=> "3wMlLnCBONHSlrxUJESPm1VwF9kBnHEGcCFt8iVR",
		    "name"=> "Test Client",
		    "type"=> "client"
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/clients/112233', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}


	public function testGetClients()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/clients', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 1, count(json_decode($this->response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/clients?sort=-name&limit=3', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 1, count(json_decode($this->response->getContent(), true)['data']) );
	}
}
