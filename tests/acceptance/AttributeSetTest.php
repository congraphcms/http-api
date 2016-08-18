<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;
use Cookbook\Core\Facades\Trunk;

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
			'--realpath' => realpath(__DIR__.'/../../vendor/lucadegasperi/oauth2-server-laravel/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/cookbook/oauth-2/database/migrations'),
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

		$this->createApplication();
		// $this->app = $this->createApplication();

		// $this->bus = $this->app->make('Illuminate\Contracts\Bus\Dispatcher');

	}

	public function tearDown()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		// parent::tearDown();
		// Trunk::forgetAll();

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

		$this->get('api/attribute-sets/1');

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(401);

		$this->seeJson([
			"message" => "Failed to authenticate because of bad credentials or an invalid authorization header.",
  			"status_code" => 401
		]);
	}

	public function testCreateAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'test-attr-set',
			'name' => 'Test Attr Set',
			'entity_type_id' => 1,
			'primary_attribute_id' => 1,
			'attributes' => [
				['id' => 1],
				['id' => 2]
			]
		];

		$this->post('api/attribute-sets', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));
		
		$this->seeStatusCode(201);

		$this->seeJson([
			'code' => 'test-attr-set',
			'name' => 'Test Attr Set',
			'entity_type_id' => 1,
			'attributes' => [
				['id' => 1, 'type' => 'attribute', 'links' => ['self' => 'http://localhost/api/attributes/1']],
				['id' => 2, 'type' => 'attribute', 'links' => ['self' => 'http://localhost/api/attributes/2']]
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
			'primary_attribute_id' => 1,
			'attributes' => [
				['id' => 1],
				['id' => 2]
			]
		];

		$this->post('api/attribute-sets', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));
		
		$this->seeStatusCode(422);

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'attribute-set' => [
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

		$this->patch('api/attribute-sets/1', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

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

		$this->put('api/attribute-sets/1', $params, $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(422);

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'attribute-set' => [
					'code' => ['The code field is required.']
				]
			]
		]);

		$this->seeInDatabase('attribute_sets', ['id' => 1, 'code' => 'attribute_set1']);

	}

	public function testDeleteAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/attribute-sets/1', [], $this->server);

		$this->seeStatusCode(204);
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->delete('api/attribute-sets/12233', [], $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}
	
	public function testFetchAttributeSet()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/attribute-sets/1', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->seeJson([
			'code' => 'attribute_set1',
			'attributes' => [
				[ 'id' => 2, 'type' => 'attribute', 'links' => ['self' => 'http://localhost/api/attributes/2'] ],
				[ 'id' => 1, 'type' => 'attribute', 'links' => ['self' => 'http://localhost/api/attributes/1'] ],
				[ 'id' => 3, 'type' => 'attribute', 'links' => ['self' => 'http://localhost/api/attributes/3'] ]
			]
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/attribute-sets/12233', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(404);

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}
	
	
	public function testGetAttributeSets()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/attribute-sets', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 4, count(json_decode($this->response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/attribute-sets?sort=-code&limit=2', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 2, count(json_decode($this->response->getContent(), true)['data']) );
	}

	public function testGetWithInclude()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$this->get('api/attribute-sets?sort=-code&limit=2&include=entity_type,attributes', $this->server);

		$this->d->dump(json_decode($this->response->getContent()));

		$this->seeStatusCode(200);

		$this->assertEquals( 2, count(json_decode($this->response->getContent(), true)['data']) );
		$this->assertEquals( 'test_fields', json_decode($this->response->getContent(), true)['data'][0]['entity_type']['code'] );
		
		
	}
}