<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;
use Congraph\Core\Facades\Trunk;

// include_once(realpath(__DIR__.'/../LaravelMocks.php'));

require_once(__DIR__ . '/../database/seeders/EavDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/LocaleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/FileDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/WorkflowDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/UserDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ScopeDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/RoleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClientDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClearDB.php');

class EntityTypeTest extends Orchestra\Testbench\TestCase
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
			'--realpath' => realpath(__DIR__.'/../../vendor/congraph/eav/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/congraph/filesystem/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/congraph/locales/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/congraph/workflows/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/congraph/users/database/migrations'),
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


		// $this->app = $this->createApplication();

		// $this->bus = $this->app->make('Illuminate\Contracts\Bus\Dispatcher');

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
			'database'	=> 'congraph_testbench',
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
		// 	'Congraph::eav', $config
		// );

		// var_dump('CONFIG SETTED');
	}

	protected function getPackageProviders($app)
	{
		return [
			'LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
			'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
			'Dingo\Api\Provider\LaravelServiceProvider',
			'Congraph\Core\CoreServiceProvider',
			'Congraph\Locales\LocalesServiceProvider',
			'Congraph\Eav\EavServiceProvider',
			'Congraph\Filesystem\FilesystemServiceProvider',
			'Congraph\Workflows\WorkflowsServiceProvider',
			'Congraph\OAuth2\OAuth2ServiceProvider',
			'Congraph\Api\ApiServiceProvider'
		];
	}

	public function testNotAuthorized() {
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/entity-types/1');

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(400);

		$this->seeJson([
			"error" => "invalid_request",
  			"status_code" => 400
		]);
	}

	public function testCreateEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'test-type',
			'endpoint' => 'test-types',
			'name' => 'Test Type',
			'plural_name' => 'Test Types',
			'workflow_id' => 1,
			'default_point_id' => 1,
			'localized_workflow' => 0,
			'multiple_sets' => 1
		];

		$this->post('api/entity-types', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(201);

		$this->seeJson([
			'code' => 'test-type',
			'endpoint' => 'test-types',
			'name' => 'Test Type',
			'plural_name' => 'Test Types',
			'workflow_id' => 1,
			'default_point_id' => 1,
			'localized_workflow' => 0,
			'multiple_sets' => 1
		]);

		$this->seeInDatabase('entity_types', ['code' => 'test-type']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => '',
			'endpoint' => 'test-types',
			'name' => 'Test Type',
			'plural_name' => 'Test Types',
			'workflow_id' => 1,
			'default_point_id' => 1,
			'localized_workflow' => 0,
			'multiple_sets' => 1
		];

		$this->post('api/entity-types', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(422);

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entity-type' => [
					'code' => ['The code field is required.']
				]
			]
		]);

	}

	public function testUpdateEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'type_code2'
		];

		$this->put('api/entity-types/1', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			'code' => 'type_code2'
		]);

		$this->seeInDatabase('entity_types', ['id' => 1, 'code' => 'type_code2']);


	}

	public function testUpdateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => ''
		];

		$this->put('api/entity-types/1', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(422);

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entity-type' => [
					'code' => ['The code field is required.']
				]
			]
		]);

		$this->seeInDatabase('entity_types', ['id' => 1, 'code' => 'tests']);

	}

	public function testDeleteEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/entity-types/1', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(204);
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/entity-types/1222333', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}

	public function testFetchEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/entity-types/1', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			'code' => 'tests',
			'attribute_sets' => [
				[ 'id' => 1, 'type' => 'attribute-set', 'links' => ['self' => 'http://localhost/api/attribute-sets/1'] ],
				[ 'id' => 2, 'type' => 'attribute-set', 'links' => ['self' => 'http://localhost/api/attribute-sets/2'] ],
				[ 'id' => 3, 'type' => 'attribute-set', 'links' => ['self' => 'http://localhost/api/attribute-sets/3'] ]
			]
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/entity-types/22333', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}


	public function testGetEntityTypes()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/entity-types', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 4, count(json_decode($this->response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/entity-types?sort=-code&limit=2', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 2, count(json_decode($this->response->getContent(), true)['data']) );

	}

	public function testGetWithInclude()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/entity-types?sort=code&limit=2&include=attribute_sets.attributes', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 2, count(json_decode($this->response->getContent(), true)['data']) );

		$this->assertTrue(is_array(json_decode($this->response->getContent(), true)['data'][0]['attribute_sets']) );

		$this->assertTrue(is_array(json_decode($this->response->getContent(), true)['data'][0]['attribute_sets'][0]['attributes']) );

		$this->assertEquals('test_text_attribute', json_decode($this->response->getContent(), true)['data'][0]['attribute_sets'][0]['attributes'][0]['code'] );

	}
}
