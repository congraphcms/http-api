<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;

// include_once(realpath(__DIR__.'/../LaravelMocks.php'));

class AttributeTest extends Orchestra\Testbench\TestCase
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
			'--class' => 'Cookbook\Eav\Seeders\TestDbSeeder'
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
		return ['Cookbook\Api\ApiServiceProvider', 'Cookbook\Eav\EavServiceProvider'];
	}

	public function testCreateAttribute()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'code',
			'field_type' => 'text',
			'localized' => 0,
			'default_value' => '',
			'unique' => 0,
			'required' => 0,
			'filterable' => 0,
			'status' => 'user_defined'
		];

		$response = $this->call('POST', '/attributes', $params);

		$this->assertEquals(201, $response->status());

		$this->seeJson([
			'code' => 'code',
			'field_type' => 'text',
			'localized' => 0,
			'default_value' => '',
			'unique' => 0,
			'required' => 0,
			'filterable' => 0,
			'status' => 'user_defined'
		]);

		$this->d->dump(json_decode($response->getContent()));

		$this->seeInDatabase('attributes', ['code' => 'code']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => '',
			'admin_label' => 'label',
			'admin_notice' => 'admin notice',
			'field_type' => 'text',
			'localized' => false,
			'default_value' => '',
			'unique' => false,
			'required' => false,
			'filterable' => false,
			'status' => 'user_defined'
		];

		$response = $this->call('POST', '/attributes', $params);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'code' => 422,
			'status' => 422,
			'message' => 'The code field is required.',
			'pointer' => '/attributes/code'
		]);

		$this->d->dump(json_decode($response->getContent()));

	}

	public function testUpdateAttribute()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'code2'
		];

		$response = $this->call('PATCH', '/attributes/1', $params);

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'code' => 'code2'
		]);

		$this->d->dump(json_decode($response->getContent()));

		$this->seeInDatabase('attributes', ['id' => 1, 'code' => 'code2']);
		

	}

	public function testUpdateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => ''
		];

		$response = $this->call('PATCH', '/attributes/1', $params);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'code' => 422,
			'status' => 422,
			'message' => 'The code field is required.',
			'pointer' => '/attributes/code'
		]);

		$this->d->dump(json_decode($response->getContent()));

		$this->seeInDatabase('attributes', ['id' => 1, 'code' => 'attribute1']);

	}

	public function testDeleteAttribute()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', '/attributes/1', []);

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'data' => 1
		]);
		

	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', '/attributes/' . 112233, []);

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'code' => 404,
			'status' => 404,
			'message' => 'There is no attribute with that ID.',
			'pointer' => '/'
		]);
		

	}
	
	public function testFetchAttribute()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', '/attributes/1', []);
		
		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'code' => 'attribute1',
			'field_type' => 'text',
			'localized' => 0,
			'default_value' => '',
			'unique' => 0,
			'required' => 1,
			'filterable' => 0,
			'status' => 'system_defined'
		]);

		$this->d->dump(json_decode($response->getContent()));
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', '/attributes/112233', []);
		
		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'code' => 404,
			'status' => 404,
			'message' => 'There is no attribute with that ID.',
			'pointer' => '/'
		]);

		$this->d->dump(json_decode($response->getContent()));
	}
	
	
	public function testGetAttributes()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', '/attributes', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->assertEquals( 7, count(json_decode($response->getContent())->data) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', '/attributes', ['sort' => '-code', 'limit' => 3]);

		$this->assertEquals( 3, count(json_decode($response->getContent())->data) );
		$this->d->dump(json_decode($response->getContent()));
		
	}
}