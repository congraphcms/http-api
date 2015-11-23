<?php

use Illuminate\Support\Facades\Cache;
use Cookbook\Core\Facades\Trunk;
use Illuminate\Support\Debug\Dumper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;

require_once(__DIR__ . '/../database/seeders/EavDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/LocaleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/FileDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/WorkflowDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClearDB.php');

class FileTest extends Orchestra\Testbench\TestCase
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

		$this->artisan('db:seed', [
			'--class' => 'FileDbSeeder'
		]);

		$this->d = new Dumper();

		Storage::deleteDir('files');
		Storage::deleteDir('uploads');

		Storage::copy('temp/test.jpg', 'files/test.jpg');
		Storage::copy('temp/test.jpg', 'files/test2.jpg');

		Storage::copy('temp/test.jpg', 'uploads/1.jpg');

	}

	public function tearDown()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		// parent::tearDown();
		Trunk::forgetAll();
		// $this->artisan('db:seed', [
		// 	'--class' => 'ClearDB'
		// ]);
		Storage::deleteDir('files');
		Storage::deleteDir('uploads');
		
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

		$app['config']->set('filesystems.default', 'local');

		$app['config']->set('filesystems.disks.local', [
			'driver'	=> 'local',
			'root'   	=> realpath(__DIR__ . '/../storage/'),
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

	public function testCreateFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'caption' => 'File test',
			'description' => 'File description'
		];

		$file = new UploadedFile(realpath(__DIR__ . '/../storage/uploads/1.jpg'), '1.jpg', 'image/jpeg', Storage::getSize('uploads/1.jpg'), null, true);

		$response = $this->call('POST', 'api/files', $params, [], ['file' => $file]);

		$this->d->dump(json_decode($response->getContent()));
		

		$this->assertEquals(201, $response->status());

		$this->assertTrue(Storage::has('files/test.jpg'));

		$this->seeJson([
			'url' => 'files/1.jpg',
			'caption' => 'File test',
			'description' => 'File description'
		]);

		$this->seeInDatabase('files', ['url' => 'files/1.jpg']);

	}

	public function testCreateFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'caption' => 'File test',
			'description' => 'File description'
		];

		$response = $this->call('POST', 'api/files', $params);

		$this->assertEquals(422, $response->status());

		$this->seeJson([
			'status_code' => 422,
			'message' => '422 Unprocessable Entity',
			'errors' => [
				'files' => ['You need to upload a file.']
			]
		]);

		$this->d->dump(json_decode($response->getContent()));

	}

	public function testUpdateFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'caption' => 'File caption changed',
			'description' => 'File description changed'
		];

		$response = $this->call('PATCH', 'api/files/1', $params);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'caption' => 'File caption changed',
			'description' => 'File description changed'
		]);
		$this->seeInDatabase('files', ['id' => 1, 'caption' => 'File caption changed', 'description' => 'File description changed']);
		

	}

	// public function testUpdateFails()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'code' => ''
	// 	];

	// 	$response = $this->call('PUT', 'api/files/1', $params);

	// 	$this->d->dump(json_decode($response->getContent()));

	// 	$this->assertEquals(422, $response->status());

	// 	$this->seeJson([
	// 		'status_code' => 422,
	// 		'message' => '422 Unprocessable Entity',
	// 		'errors' => [
	// 			'attributes' => [
	// 				'code' => ['The code field is required.']
	// 			]
	// 		]
	// 	]);

	// 	$this->seeInDatabase('attributes', ['id' => 1, 'code' => 'attribute1']);

	// }

	public function testDeleteFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/files/1', []);

		$this->assertEquals(204, $response->status());

		$this->assertFalse(Storage::has('files/test.jpg'));

		$this->dontSeeInDatabase('files', ['id' => 1]);
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/files/1233', []);

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
		

	}
	
	public function testFetchFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/files/1', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'id' => 1,
			'url' => 'files/test.jpg',
			'name' => 'test.jpg',
		]);
	}

	public function testFetchFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/files/112233', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);
	}
	
	
	public function testGetFiles()
	{
		fwrite(STDOUT, __METHOD__ . "\n");	

		$response = $this->call('GET', 'api/files', []);

		$this->d->dump(json_decode($response->getContent()));
		
		$this->assertEquals(200, $response->status());

		$this->assertEquals( 2, count(json_decode($response->getContent())) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/files', ['sort' => '-name']);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 2, count(json_decode($response->getContent())) );
	}
}