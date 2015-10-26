<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;

// include_once(realpath(__DIR__.'/../LaravelMocks.php'));

class AttributeSetTest extends Orchestra\Testbench\TestCase
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


		// $this->app = $this->createApplication();

		// $this->bus = $this->app->make('Illuminate\Contracts\Bus\Dispatcher');

	}

	public function tearDown()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		// parent::tearDown();
		
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

	public function testCreateAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'test-attr-set',
			'name' => 'Test Attr Set',
			'entity_type_id' => 1,
			'attributes' => [
				['id' => 1],
				['id' => 2]
			]
		];

		$response = $this->call('POST', 'api/attribute-sets', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(201, $response->status());

		$this->seeJson([
			'code' => 'test-attr-set',
			'name' => 'Test Attr Set',
			'entity_type_id' => 1,
			'attributes' => [
				['id' => 1, 'type' => 'attribute'],
				['id' => 2, 'type' => 'attribute']
			]
		]);

		$this->seeInDatabase('attribute_sets', ['code' => 'test-attr-set']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => '',
			'name' => 'Test Attr Set',
			'entity_type_id' => 1,
			'attributes' => [
				['id' => 1],
				['id' => 2]
			]
		];

		$response = $this->call('POST', 'api/attribute-sets', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'attribute-sets' => [
					'code' => ['The code field is required.']
				]
			]
		]);
	}

	public function testUpdateAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'set_code2'
		];

		$response = $this->call('PATCH', 'api/attribute-sets/1', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'code' => 'set_code2'
		]);

		$this->seeInDatabase('attribute_sets', ['id' => 1, 'code' => 'set_code2']);
		

	}

	public function testUpdateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => ''
		];

		$response = $this->call('PUT', 'api/attribute-sets/1', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'attribute-sets' => [
					'code' => ['The code field is required.']
				]
			]
		]);

		$this->seeInDatabase('attribute_sets', ['id' => 1, 'code' => 'attribute_set1']);

	}

	public function testDeleteAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/attribute-sets/1', []);

		$this->assertEquals(204, $response->status());
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/attribute-sets/112233', []);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}
	
	public function testFetchAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/attribute-sets/1', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'code' => 'attribute_set1',
			'attributes' => [
				[ 'id' => 2, 'type' => 'attribute' ],
				[ 'id' => 1, 'type' => 'attribute' ],
				[ 'id' => 3, 'type' => 'attribute' ]
			]
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/attribute-sets/112233', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}
	
	
	public function testGetAttributeSets()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/attribute-sets', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->assertEquals( 3, count(json_decode($response->getContent())) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/attribute-sets', ['sort' => '-code', 'limit' => 2]);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 2, count(json_decode($response->getContent())) );
	}

	public function testGetWithInclude()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/attribute-sets', ['limit' => 2, 'include' => 'attributes, entity_type']);
		$this->d->dump(json_decode($response->getContent()));
		$this->assertEquals( 2, count(json_decode($response->getContent())) );
		
		
	}
}