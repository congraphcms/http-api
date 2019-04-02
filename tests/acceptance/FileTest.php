<?php

use Illuminate\Support\Facades\Cache;
use Congraph\Core\Facades\Trunk;
use Illuminate\Support\Debug\Dumper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

require_once(__DIR__ . '/../database/seeders/EavDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/LocaleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/FileDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/WorkflowDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/UserDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ScopeDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/RoleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClientDbSeeder.php');
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
			'--realpath' => realpath(__DIR__.'/../../vendor/lucadegasperi/oauth2-server-laravel/database/migrations'),
		]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/congraph/oauth2/database/migrations'),
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

		$app['config']->set('filesystems.default', 'local');

		$app['config']->set('filesystems.disks.local', [
			'driver'	=> 'local',
			'root'   	=> realpath(__DIR__ . '/../storage/'),
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

	// public function testNotAuthorized() {
	// 	fwrite(STDOUT, __METHOD__ . "\n");
	//
	// 	$this->get('api/files/1');
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

	public function testCreateFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'caption' => 'File test',
			'description' => 'File description'
		];

		$file = new UploadedFile(realpath(__DIR__ . '/../storage/uploads/1.jpg'), '1.jpg', 'image/jpeg', Storage::getSize('uploads/1.jpg'), null, true);

		$response = $this->call('POST', 'api/files', $params, [], ['file' => $file], $this->server);

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

		$response = $this->call('POST', 'api/files', $params, [], [], $this->server);

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

		$response = $this->call('PATCH', 'api/files/1', $params, [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->seeJson([
			'caption' => 'File caption changed',
			'description' => 'File description changed'
		]);
		$this->seeInDatabase('files', ['id' => 1, 'caption' => 'File caption changed', 'description' => 'File description changed']);


	}

	public function testDeleteFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/files/1', [], [], [], $this->server);

		$this->assertEquals(204, $response->status());

		$this->assertFalse(Storage::has('files/test.jpg'));

		$this->dontSeeInDatabase('files', ['id' => 1]);
	}

	public function testDeleteFails()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('DELETE', 'api/files/1233', [], [], [], $this->server);

		$this->assertEquals(404, $response->status());

		$this->seeJson([
			'status_code' => 404,
			'message' => '404 Not Found'
		]);


	}

	public function testFetchFile()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/files/1', [], [], [], $this->server);

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

		$response = $this->call('GET', 'api/files/112233', [], [], [], $this->server);

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

		$response = $this->call('GET', 'api/files', [], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals(200, $response->status());

		$this->assertEquals( 2, count(json_decode($response->getContent(), true)['data']) );
	}

	public function testGetParams()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$response = $this->call('GET', 'api/files', ['sort' => '-name'], [], [], $this->server);

		$this->d->dump(json_decode($response->getContent()));

		$this->assertEquals( 2, count(json_decode($response->getContent(), true)['data']) );
	}

	public function testFileServe()
	{
		fwrite(STDOUT, __METHOD__ . "\n");
		$this->refreshApplication();
		$response = $this->call('GET', 'api/files/test.jpg', [], [], [], $this->server);

		$content = $response->getContent();

		$contentType = 'image/jpeg';
		$fileContent = Storage::get('/files/test.jpg');
		$this->assertEquals($fileContent, $content);
		$this->assertEquals($contentType, $response->headers->get('Content-Type'));


		$this->refreshApplication();
		$this->get('api/files/test.jpg?v=admin_thumb', $this->server);

		$content = $this->response->getContent();

		// $this->d->dump($response->getContent());

		$thumbUrl = realpath(__DIR__ . '/../storage/') . '/files/test.jpg';

		$thumbHandler = new \Congraph\Filesystem\Handlers\Images\AdminThumbHandler();
		$thumbContent = $thumbHandler->handle($thumbUrl);
		$this->assertEquals($thumbContent, $content);
		$this->assertEquals($contentType, $this->response->headers->get('Content-Type'));
	}
}
