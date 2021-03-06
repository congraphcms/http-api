<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Http\Request;

use LucaDegasperi\OAuth2Server\Facades\Authorizer;

require_once(__DIR__ . '/../database/seeders/UserDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ScopeDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/RoleDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClientDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClearDB.php');

class OAuthTest extends Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		parent::setUp();

		// $this->artisan('migrate', [
		// 	'--database' => 'testbench',
		// 	'--realpath' => realpath(__DIR__.'/../migrations'),
		// ]);

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--realpath' => realpath(__DIR__.'/../../vendor/lucadegasperi/oauth2-server-laravel/database/migrations'),
		]);

		// $this->artisan('migrate', [
		// 	'--database' => 'testbench',
		// 	'--realpath' => realpath(__DIR__.'/../../vendor/congraph/users/database/migrations'),
		// ]);

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

		$this->clientId = 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR';
		$this->clientSecret = '3wMlLnCBONHSlrxUJESPm1VwF9kBnHEGcCFt8iVR';
	}

	public function tearDown()
	{
		// $this->artisan('db:seed', [
		// 	'--class' => 'ClearDB'
		// ]);
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

		$app['config']->set('auth.driver', 'repository');

		$app['config']->set('oauth2.grant_types', [
			'client_credentials' => [
		        'class' => '\League\OAuth2\Server\Grant\ClientCredentialsGrant',
		        'access_token_ttl' => 3600
		    ],
			'password' => [
		        'class' => '\League\OAuth2\Server\Grant\PasswordGrant',
		        'callback' => '\Congraph\OAuth2\PasswordGrantVerifier@verify',
		        'access_token_ttl' => 3600
		    ],
		    'refresh_token' => [
		        'class' => '\League\OAuth2\Server\Grant\RefreshTokenGrant',
		        'access_token_ttl' => 3600,
		        'refresh_token_ttl' => 604800,
		        'rotate_refresh_tokens' => true
		    ]
		]);
	}

	protected function getPackageProviders($app)
	{
		return [
			'LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
			'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
			'Congraph\Core\CoreServiceProvider',
			'Congraph\OAuth2\OAuth2ServiceProvider',


		];
	}
	public function testTest() {
		
	}
	// /**
	//  * @expectedException \League\OAuth2\Server\Exception\InvalidRequestException
	//  */
	// public function testRevokeTokenInvalidTokenType()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'grant_type' => 'client_credentials',
	// 		'client_id' => $this->clientId,
	// 		'client_secret' => $this->clientSecret,
	// 		'scope' => 'manage_content'
	// 	];

	// 	$response = $this->call('POST', 'oauth/access_token', $params);
	// 	$data = json_decode($response->getContent(), true);
	// 	$access_token = $data['access_token'];
	// 	$refresh_token = $data['refresh_token'];

	// 	$server = [
	// 		'HTTP_Authorization' => 'Bearer ' . $access_token
	// 	];

	// 	$revokeParams = [
	// 		'token' => $access_token,
	// 		'token_type' => 'invalid_token'
	// 	];

	// 	$this->refreshApplication();

	// 	$this->post('oauth/revoke_token', $revokeParams, $server);

	// 	// $this->assertEquals('Voila!', $response->getContent());

	// 	// $this->d->dump($this->response->getContent());
	// }

	// /**
	//  * @expectedException \League\OAuth2\Server\Exception\InvalidRequestException
	//  */
	// public function testRevokeTokenInvalidToken()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'grant_type' => 'client_credentials',
	// 		'client_id' => $this->clientId,
	// 		'client_secret' => $this->clientSecret,
	// 		'scope' => 'manage_content'
	// 	];

	// 	$response = $this->call('POST', 'oauth/access_token', $params);
	// 	$data = json_decode($response->getContent(), true);
	// 	$access_token = $data['access_token'];
	// 	// $refresh_token = $data['refresh_token'];

	// 	$server = [
	// 		'HTTP_Authorization' => 'Bearer ' . $access_token
	// 	];

	// 	$revokeParams = [
	// 		'token_type' => 'invalid_token'
	// 	];

	// 	$this->refreshApplication();

	// 	$this->post('oauth/revoke_token', $revokeParams, $server);
	// }

	// public function testRevokeAccessToken()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'grant_type' => 'client_credentials',
	// 		'client_id' => $this->clientId,
	// 		'client_secret' => $this->clientSecret,
	// 		'scope' => 'manage_content'
	// 	];

	// 	$response = $this->call('POST', 'oauth/access_token', $params);
	// 	$data = json_decode($response->getContent(), true);
	// 	$access_token = $data['access_token'];
	// 	// $refresh_token = $data['refresh_token'];

	// 	$server = [
	// 		'HTTP_Authorization' => 'Bearer ' . $access_token
	// 	];

	// 	$revokeParams = [
	// 		'token' => $access_token,
	// 		'token_type' => 'access_token'
	// 	];

	// 	$this->refreshApplication();

	// 	$this->post('oauth/revoke_token', $revokeParams, $server);

	// 	$this->seeStatusCode(200);

	// 	$this->dontSeeInDatabase('oauth_access_tokens', ['id' => $access_token]);
	// }

	// public function testRevokeRefreshToken()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'grant_type' => 'password',
	// 		'client_id' => $this->clientId,
	// 		'client_secret' => $this->clientSecret,
	// 		'username' => 'jane.doe@email.com',
	// 		'password' => 'secret123'
	// 	];

	// 	$response = $this->call('POST', 'oauth/access_token', $params);
	// 	$data = json_decode($response->getContent(), true);
	// 	$access_token = $data['access_token'];
	// 	$refresh_token = $data['refresh_token'];

	// 	$server = [
	// 		'HTTP_Authorization' => 'Bearer ' . $access_token
	// 	];

	// 	$revokeParams = [
	// 		'token' => $refresh_token,
	// 		'token_type' => 'refresh_token'
	// 	];

	// 	$this->refreshApplication();

	// 	$this->post('oauth/revoke_token', $revokeParams, $server);

	// 	$this->seeStatusCode(200);

	// 	$this->dontSeeInDatabase('oauth_refresh_tokens', ['id' => $refresh_token]);
	// 	$this->dontSeeInDatabase('oauth_access_tokens', ['id' => $access_token]);
	// }

	// public function testGetOwnerClient()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'grant_type' => 'client_credentials',
	// 		'client_id' => $this->clientId,
	// 		'client_secret' => $this->clientSecret,
	// 		'scope' => 'manage_content'
	// 	];

	// 	$response = $this->call('POST', 'oauth/access_token', $params);
	// 	$data = json_decode($response->getContent(), true);
	// 	$access_token = $data['access_token'];
	// 	// $refresh_token = $data['refresh_token'];

	// 	$server = [
	// 		'HTTP_Authorization' => 'Bearer ' . $access_token
	// 	];

	// 	// $principalParams = [
	// 	// 	'token' => $refresh_token,
	// 	// 	'token_type' => 'refresh_token'
	// 	// ];

	// 	$this->refreshApplication();

	// 	$this->get('oauth/owner', $server);

	// 	$this->seeStatusCode(200);

	// 	$this->d->dump(json_decode($this->response->getContent()));
	// }

	// public function testGetOwnerUser()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'grant_type' => 'password',
	// 		'client_id' => $this->clientId,
	// 		'client_secret' => $this->clientSecret,
	// 		'username' => 'jane.doe@email.com',
	// 		'password' => 'secret123'
	// 	];

	// 	$response = $this->call('POST', 'oauth/access_token', $params);
	// 	$data = json_decode($response->getContent(), true);
	// 	$access_token = $data['access_token'];
	// 	// $refresh_token = $data['refresh_token'];

	// 	$server = [
	// 		'HTTP_Authorization' => 'Bearer ' . $access_token
	// 	];

	// 	// $principalParams = [
	// 	// 	'token' => $refresh_token,
	// 	// 	'token_type' => 'refresh_token'
	// 	// ];

	// 	$this->refreshApplication();

	// 	$this->get('oauth/owner', $server);

	// 	$this->seeStatusCode(200);

	// 	$this->d->dump(json_decode($this->response->getContent()));
	// }
}
