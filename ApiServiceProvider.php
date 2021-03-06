<?php
/*
 * This file is part of the congraph/cms package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Api;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Auth\Provider\OAuth2;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

/**
 * ApiServiceProvider service provider for Congraph API
 * 
 * It will register all API components to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/cms
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ApiServiceProvider extends ServiceProvider {

	/**
	* Register
	* 
	* @return void
	*/
	public function register() {
		$this->mergeConfigFrom(realpath(__DIR__ . '/config/config.php'), 'cb.api');
		$this->registerServiceProviders();

		// $this->app->singleton('Congraph\Api\Dispatcher', function($app){
		// 	return new Dispatcher(
		// 		$app->make('Illuminate\Contracts\Bus\Dispatcher'),
		// 		$app->make('Illuminate\Contracts\Events\Dispatcher')
		// 	);
		// });

		$this->app['router']->middleware('cb.gettype', 'Congraph\Api\Http\Middleware\GetEntityType');
		$this->app['router']->middleware('cb.cors', 'Congraph\Api\Http\Middleware\CORS');
	}

	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot() {
		$this->publishes([
			__DIR__.'/config/config.php' => config_path('cb.api.php'),
		]);
		include __DIR__ . '/Http/routes.php';

		$this->extendDingoAuth();
		$this->registerOAuthExceptionHandler();

		// $this->mapApiCommands();
	}

	/**
	 * Register Service Providers for this package
	 * 
	 * @return void
	 */
	protected function registerServiceProviders()
	{

		// // Events
		// // -----------------------------------------------------------------------------
		// $this->app->register('Congraph\Api\Events\EventsServiceProvider');

		// // Handlers
		// // -----------------------------------------------------------------------------
		// $this->app->register('Congraph\Api\Handlers\HandlersServiceProvider');
	}

	/**
	 * Extend Dingo API Auth Class
	 *
	 * @return void
	 */
	public function extendDingoAuth() {
		// $app = $this->app;
		$this->app[Auth::class]->extend('oauth', function ($app) {
            $provider = new OAuth2($app['oauth2-server.authorizer']->getChecker());

            $provider->setUserResolver(function ($id) use ($app){
                $userRepository = $app->make('Congraph\Contracts\OAuth2\UserRepositoryContract');
                $user = $userRepository->fetch($id);
                return $user;
            });

            $provider->setClientResolver(function ($id) use ($app) {
                $clientRepository = $app->make('Congraph\Contracts\OAuth2\ClientRepositoryContract');
                $client = $clientRepository->fetch($id);
                return $client;
            });

            return $provider;
        });
	}

	/**
	 * Register Middleware
	 * 
	 * @return void
	 */
	protected function registerOAuthExceptionHandler()
	{
		$this->app->make('Dingo\Api\Exception\Handler')->register(
			function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
				$previous = $exception->getPrevious();
		    	if($previous instanceof \League\OAuth2\Server\Exception\OAuthException) {
		    		return Response::make(
			    		[
				    		'error' => $previous->errorType, 
				    		'message' => $previous->getMessage(),
				    		'status_code' => 401
			    		], 
			    		401
			    	);
		    	}

		    	return Response::make(
		    		[
		    			'message' => $exception->getMessage(),
		    			'status_code' => $exception->getStatusCode()
		    		],
		    		$exception->getStatusCode()
		    	);
		    	
			}
		);

		$this->app->make('Dingo\Api\Exception\Handler')->register(
			function (\League\OAuth2\Server\Exception\InvalidScopeException $exception) {
	    		return Response::make(
		    		[
			    		'error' => $exception->errorType, 
			    		'message' => $exception->getMessage(),
			    		'status_code' => $exception->httpStatusCode
		    		], 
		    		$exception->httpStatusCode
		    	);
		    	
			}
		);
	}

	/**
	 * Maps Command Handlers
	 *
	 * @return void
	 */
	public function mapApiCommands() {
		
		$mappings = [
			// Attributes
			'attributes.create' 		=> 'Congraph\Eav\Commands\Attributes\AttributeCreateCommand',
			'attributes.update' 		=> 'Congraph\Eav\Commands\Attributes\AttributeUpdateCommand',
			'attributes.delete' 		=> 'Congraph\Eav\Commands\Attributes\AttributeDeleteCommand',
			'attributes.fetch' 			=> 'Congraph\Eav\Commands\Attributes\AttributeFetchCommand',
			'attributes.get' 			=> 'Congraph\Eav\Commands\Attributes\AttributeGetCommand',

			// Attribute sets
			'attribute-sets.create' 	=> 'Congraph\Eav\Commands\AttributeSets\AttributeSetCreateCommand',
			'attribute-sets.update' 	=> 'Congraph\Eav\Commands\AttributeSets\AttributeSetUpdateCommand',
			'attribute-sets.delete' 	=> 'Congraph\Eav\Commands\AttributeSets\AttributeSetDeleteCommand',
			'attribute-sets.fetch' 		=> 'Congraph\Eav\Commands\AttributeSets\AttributeSetFetchCommand',
			'attribute-sets.get' 		=> 'Congraph\Eav\Commands\AttributeSets\AttributeSetGetCommand',

			// Entity types
			'entity-types.create' 		=> 'Congraph\Eav\Commands\EntityTypes\EntityTypeCreateCommand',
			'entity-types.update' 		=> 'Congraph\Eav\Commands\EntityTypes\EntityTypeUpdateCommand',
			'entity-types.delete' 		=> 'Congraph\Eav\Commands\EntityTypes\EntityTypeDeleteCommand',
			'entity-types.fetch' 		=> 'Congraph\Eav\Commands\EntityTypes\EntityTypeFetchCommand',
			'entity-types.get' 			=> 'Congraph\Eav\Commands\EntityTypes\EntityTypeGetCommand'
		];

		$this->app->make('Congraph\Api\Dispatcher')->map($mappings, 'commands');

		$mappings = [
			'attribute' 				=> 'attributes.get',
			'attribute-set' 			=> 'attribute-sets.get',
			'entity-type' 				=> 'entity-types.get'
		];

		$this->app->make('Congraph\Api\Dispatcher')->map($mappings, 'resolvers');
	}

}