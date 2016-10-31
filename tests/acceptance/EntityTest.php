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

class EntityTest extends Orchestra\Testbench\TestCase
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

	// public function testNotAuthorized() {
	// 	fwrite(STDOUT, __METHOD__ . "\n");
	//
	// 	$this->get('api/entities/1');
	//
	// 	$this->d->dump(json_decode($this->response->getContent()));
	//
	// 	$this->seeStatusCode(400);
	//
	// 	$this->seeJson([
	// 		"error" => "invalid_request",
  // 			"status_code" => 400
	// 	]);
	// }

	public function testCreateEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'entity_type' => 'tests',
			'attribute_set' => ['id' => 1],
			'fields' => [
				'attribute1' => 'some_unique_value',
				'attribute2' => ''
			]
		];

		$response = $this->call('POST', 'api/entities', $params, [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));


		$this->assertEquals(201, $response->status());

		$this->seeJson([
			'fields' => [
				'attribute1' => 'some_unique_value',
				'attribute2' => '',
				'attribute3' => ["en_US" => "", "fr_FR" => ""]
			]
		]);

		$this->seeInDatabase('attribute_values_text', ['value' => 'some_unique_value']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'entity_type' => 'tests',
			'attribute_set' => ['id' => 1],
			'fields' => [
				'attribute1' => ''
			]
		];

		$response = $this->call('POST', 'api/entities', $params, [], [], $this->server);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entity' => [
					'fields' => [
						'attribute1' => [ 'This field is required.' ]
					]
				]
			]
		]);

		$this->d->dump(json_decode($response->getContent()));

	}

	public function testUpdateEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'fields' => [
				'attribute1' => 'changed value'
			]
		];

		$response = $this->call('PATCH', 'api/entities/1', $params, [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'attribute1' => 'changed value'
		]);
		$this->seeInDatabase('attribute_values_text', ['value' => 'changed value']);


	}

	public function testUpdateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'locale_id' => 0,
			'fields' => [
				'attribute1' => ''
			]
		];

		$response = $this->call('PUT', 'api/entities/1', $params, [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entity' => [
					'fields' => [
						'attribute1' => [ 'This field is required.' ]
					]
				]
			]
		]);

		$this->seeInDatabase('attribute_values_text', ['id' => 1, 'value' => 'value1']);

	}

	public function testDeleteEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/entities/1', [], [], [], $this->server);

		$this->assertEquals(204, $response->status());

	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/entities/1233', [], [], [], $this->server);

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);


	}

	public function testFetchEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities/1', [], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			"id" => 1,
			"entity_type_id" => 1,
			"attribute_set_id" => 1,
			"entity_type" => "tests",
			"type" => "entity",
			'fields' => [
				'attribute1' => 'value1',
				'attribute2' => 'value2',
				'attribute3' => [
					'en_US' => 'value3-en',
					'fr_FR' => 'value3-fr'
				]
			]
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities/112233', [], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}


	public function testGetEntities()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities', [], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->assertEquals( 4, count(json_decode($response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities', ['sort' => ['fields.attribute3'], 'limit' => 3, 'offset' => 0], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 3, count(json_decode($response->getContent(), true)['data']) );
	}

	public function testGetFilters()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$filter = [ 'fields.attribute1' => ['in' => ['value12']] ];

		$response = $this->call('GET', 'api/entities', ['filter' => $filter], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 1, count(json_decode($response->getContent(), true)['data']) );
		$this->assertEquals( 'value12', json_decode($response->getContent(), true)['data'][0]['fields']['attribute1'] );

	}

	public function testTypeRoutes()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/tests', [], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 3, count(json_decode($response->getContent(), true)['data']) );
		$this->assertEquals( 1, json_decode($response->getContent(), true)['data'][0]['entity_type_id'] );

	}
}
