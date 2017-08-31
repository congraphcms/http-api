<?php

use Cookbook\Api\Linker;
use Cookbook\Core\Facades\Trunk;
use Cookbook\Core\Repositories\Collection;
use Cookbook\Core\Repositories\Model;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Support\Facades\Cache;

class LinkerTest extends Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		// fwrite(STDOUT, __METHOD__ . "\n");
		parent::setUp();

		$this->d = new Dumper();

	}

	public function tearDown()
	{

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
			'Cookbook\Core\CoreServiceProvider',
			'Cookbook\Locales\LocalesServiceProvider',
			'Cookbook\Eav\EavServiceProvider',
			'Cookbook\Filesystem\FilesystemServiceProvider',
			'Cookbook\Workflows\WorkflowsServiceProvider',
			'Cookbook\OAuth2\OAuth2ServiceProvider',
			// 'Cookbook\Users\UsersServiceProvider', 
			'LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
			'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
			'Cookbook\Api\ApiServiceProvider',
			'Dingo\Api\Provider\LaravelServiceProvider'
		];
	}

	public function testQueryBuild()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$q = http_build_query(['a' => 123, 'b' => 'abc']);
		$this->assertEquals("a=123&b=abc", $q);
		$this->d->dump($q);

		$q = http_build_query(['a' => [1,2,3], 'b' => 'abc']);
		$this->assertEquals("a[0]=1&a[1]=2&a[2]=3&b=abc", urldecode($q));
		$this->d->dump($q);

		$q = http_build_query(['a' => ['c'=>1, 'd'=>2], 'b' => 'abc']);
		$this->assertEquals("a[c]=1&a[d]=2&b=abc", urldecode($q));
		$this->d->dump($q);
	}

	public function testGetLinks()
	{
		fwrite(STDOUT, __METHOD__ . "\n");
		$data = new StdClass();
		$data->id = 1;
		$data->type = 'attribute';
		$data->code = 'title';
		$data->field_type = 'text';
		$model = new Model($data);
		$meta = [
			'include' => 'options',
			'locale' => 'en_US'
		];
		$model->setMeta($meta);
		$links = Linker::getLinks($model, 'attribute');
		$this->d->dump($links);

		$data = new StdClass();
		$data->id = 1;
		$data->type = 'attribute';
		$data->code = 'title';
		$data->field_type = 'text';

		$data = [$data];
		$collection = new Collection($data);

		$meta = [
			'filter' => ['fields.title' => '123'],
			'offset' => 10,
			'limit' => 10,
			'total' => 40,
			'sort' => '-created_at'
		];

		$collection->setMeta($meta);
		$links = Linker::getLinks($collection, 'attribute');
		$this->d->dump($links);


		$meta = [
			'filter' => ['fields.title' => '123'],
			'offset' => 10,
			'limit' => 0,
			'total' => 40,
			'sort' => '-created_at'
		];

		$collection->setMeta($meta);
		$links = Linker::getLinks($collection, 'attribute');
		$this->d->dump($links);

		$meta = [
			'filter' => ['fields.title' => '123'],
			'offset' => 0,
			'limit' => 10,
			'total' => 40,
			'sort' => '-created_at'
		];

		$collection->setMeta($meta);
		$links = Linker::getLinks($collection, 'attribute');
		$this->d->dump($links);

		$meta = [
			'filter' => ['fields.title' => '123'],
			'offset' => 30,
			'limit' => 10,
			'total' => 40,
			'sort' => '-created_at'
		];

		$collection->setMeta($meta);
		$links = Linker::getLinks($collection, 'attribute');
		$this->d->dump($links);

		$meta = [
			'filter' => ['fields.title' => '123'],
			'offset' => 0,
			'limit' => 10,
			'total' => 5,
			'sort' => '-created_at'
		];

		$collection->setMeta($meta);
		$links = Linker::getLinks($collection, 'attribute');
		$this->d->dump($links);
	}

}
