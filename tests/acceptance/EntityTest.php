<?php

use Illuminate\Support\Facades\Cache;
use Cookbook\Core\Facades\Trunk;
use Illuminate\Support\Debug\Dumper;

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
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/eav/migrations'),
		]);

		$this->artisan('db:seed', [
			'--class' => 'Cookbook\Api\Seeders\TestDbSeeder'
		]);

		$this->d = new Dumper();

	}

	public function tearDown()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		// parent::tearDown();
		Trunk::forgetAll();
		$this->artisan('migrate:reset');
		
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
		return ['Cookbook\Api\ApiServiceProvider', 'Cookbook\Eav\EavServiceProvider', 'Cookbook\Core\CoreServiceProvider', 'Dingo\Api\Provider\LaravelServiceProvider'];
	}

	public function testCreateEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'type' => 'tests',
			'attribute_set' => ['id' => 1],
			'locale_id' => 0,
			'fields' => [
				'attribute1' => 'some_unique_value',
				'attribute2' => ''
			]
		];

		$response = $this->call('POST', 'api/entities', $params);

		$this->d->dump(json_decode($response->getContent()));
		

		$this->assertEquals(201, $response->status());

		$this->seeJson([
			'fields' => [
				'attribute1' => 'some_unique_value',
				'attribute2' => '',
				'attribute3' => ''
			]
		]);

		$this->seeInDatabase('attribute_values_varchar', ['value' => 'some_unique_value']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'type' => 'tests',
			'attribute_set' => ['id' => 1],
			'locale_id' => 0,
			'fields' => [
				'attribute1' => ''
			]
		];

		$response = $this->call('POST', 'api/entities', $params);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entities' => [
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
			'locale_id' => 0,
			'fields' => [
				'attribute1' => 'changed value'
			]
		];

		$response = $this->call('PATCH', 'api/entities/1', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'attribute1' => 'changed value'
		]);
		$this->seeInDatabase('attribute_values_varchar', ['value' => 'changed value']);
		

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

		$response = $this->call('PUT', 'api/entities/1', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'entities' => [
					'fields' => [
						'attribute1' => [ 'This field is required.' ]
					]
				]
			]
		]);

		$this->seeInDatabase('attribute_values_varchar', ['id' => 1, 'value' => 'value1']);

	}

	public function testDeleteEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/entities/1', []);

		$this->assertEquals(204, $response->status());

	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/entities/1233', []);

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
		

	}
	
	public function testFetchEntity()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities/1', []);

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
				'attribute3' => 'value3'
			]
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities/112233', []);

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

		$response = $this->call('GET', 'api/entities', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->assertEquals( 6, count(json_decode($response->getContent())) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/entities', ['sort' => ['fields.attribute3'], 'limit' => 3, 'offset' => 0]);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 3, count(json_decode($response->getContent(), true)) );
		$this->assertEquals( 'value23', json_decode($response->getContent(), true)[0]['fields']['attribute3'] );
		$this->assertEquals( 'value33', json_decode($response->getContent(), true)[2]['fields']['attribute3'] );
	}

	public function testGetFilters()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$filter = [ 'fields.attribute1' => ['in' => ['value21']] ];

		$response = $this->call('GET', 'api/entities', ['filter' => $filter]);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals( 1, count(json_decode($response->getContent(), true)) );
		$this->assertEquals( 'value21', json_decode($response->getContent(), true)[0]['fields']['attribute1'] );

	}

	public function testTypeRoutes()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/tests', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals( 3, count(json_decode($response->getContent(), true)) );
		$this->assertEquals( 1, json_decode($response->getContent(), true)[0]['entity_type_id'] );

	}
}