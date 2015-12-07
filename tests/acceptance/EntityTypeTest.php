<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;
use Cookbook\Core\Facades\Trunk;

// include_once(realpath(__DIR__.'/../LaravelMocks.php'));

require_once(__DIR__ . '/../database/seeders/EavDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/LocaleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/FileDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/WorkflowDbSeeder.php');
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

		$this->artisan('db:seed', [
			'--class' => 'EavDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'LocaleDbSeeder'
		]);

		$this->artisan('db:seed', [
			'--class' => 'WorkflowDbSeeder'
		]);
		$this->d = new Dumper();


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
			'Cookbook\Core\CoreServiceProvider', 
			'Cookbook\Locales\LocalesServiceProvider', 
			'Cookbook\Eav\EavServiceProvider', 
			'Cookbook\Filesystem\FilesystemServiceProvider',
			'Cookbook\Workflows\WorkflowsServiceProvider',
			'Cookbook\Api\ApiServiceProvider',
			'Dingo\Api\Provider\LaravelServiceProvider'
		];
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

		$response = $this->call('POST', 'api/entity-types', $params);

		$this->assertEquals(201, $response->status());

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

		$this->d->dump(json_decode($response->getContent()));

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

		$response = $this->call('POST', 'api/entity-types', $params);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entity-type' => [
					'code' => ['The code field is required.']
				]
			]
		]);

		$this->d->dump(json_decode($response->getContent()));

	}

	public function testUpdateEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'type_code2'
		];

		$response = $this->call('PATCH', 'api/entity-types/1', $params);

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'code' => 'type_code2'
		]);

		$this->d->dump(json_decode($response->getContent()));

		$this->seeInDatabase('entity_types', ['id' => 1, 'code' => 'type_code2']);
		

	}

	public function testUpdateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => ''
		];

		$response = $this->call('PATCH', 'api/entity-types/1', $params);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entity-type' => [
					'code' => ['The code field is required.']
				]
			]
		]);

		$this->d->dump(json_decode($response->getContent()));

		$this->seeInDatabase('entity_types', ['id' => 1, 'code' => 'tests']);

	}

	public function testDeleteEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/entity-types/1', []);

		$this->assertEquals(204, $response->status());
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/entity-types/112233', []);

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
		
		$this->d->dump(json_decode($response->getContent()));
	}
	
	public function testFetchEntityType()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entity-types/1', []);
		
		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'code' => 'tests',
			'attribute_sets' => [
				[ 'id' => 1, 'type' => 'attribute-set', 'links' => ['self' => 'http://localhost/api/attribute-sets/1'] ],
				[ 'id' => 2, 'type' => 'attribute-set', 'links' => ['self' => 'http://localhost/api/attribute-sets/2'] ],
				[ 'id' => 3, 'type' => 'attribute-set', 'links' => ['self' => 'http://localhost/api/attribute-sets/3'] ]
			]
		]);

		$this->d->dump(json_decode($response->getContent()));
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entity-types/112233', []);
		
		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);

		$this->d->dump(json_decode($response->getContent()));
	}
	
	
	public function testGetEntityTypes()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entity-types', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->assertEquals( 4, count(json_decode($response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entity-types', ['sort' => '-code', 'limit' => 2]);

		$this->assertEquals( 2, count(json_decode($response->getContent(), true)['data']) );
		$this->d->dump(json_decode($response->getContent()));
		
	}

	public function testGetWithInclude()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entity-types', ['limit' => 2, 'include' => 'attribute_sets.attributes']);

		$this->assertEquals( 2, count(json_decode($response->getContent(), true)['data']) );
		$this->d->dump(json_decode($response->getContent()));
		
	}
}