<?php
/*
 * This file is part of the cookbook/cms package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api;

use Illuminate\Support\ServiceProvider;

/**
 * ApiServiceProvider service provider for Cookbook API
 * 
 * It will register all API components to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/cms
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
		$this->mergeConfigFrom(realpath(__DIR__ . '/config/cookbook.php'), 'cookbook');
		$this->mergeConfigFrom(realpath(__DIR__ . '/config/api.php'), 'api');
		$this->registerServiceProviders();

		// $this->app->singleton('Cookbook\Api\Dispatcher', function($app){
		// 	return new Dispatcher(
		// 		$app->make('Illuminate\Contracts\Bus\Dispatcher'),
		// 		$app->make('Illuminate\Contracts\Events\Dispatcher')
		// 	);
		// });

		$this->app['router']->middleware('cb.gettype', 'Cookbook\Api\Http\Middleware\GetEntityType');
	}

	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot() {
		$this->publishes([
			__DIR__.'/config/cookbook.php' => config_path('cookbook.php'),
		]);
		$this->publishes([
			__DIR__.'/config/api.php' => config_path('api.php'),
		]);
		include __DIR__ . '/Http/routes.php';

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
		// $this->app->register('Cookbook\Api\Events\EventsServiceProvider');

		// // Handlers
		// // -----------------------------------------------------------------------------
		// $this->app->register('Cookbook\Api\Handlers\HandlersServiceProvider');
	}

	/**
	 * Maps Command Handlers
	 *
	 * @return void
	 */
	public function mapApiCommands() {
		
		$mappings = [
			// Attributes
			'attributes.create' 		=> 'Cookbook\Eav\Commands\Attributes\AttributeCreateCommand',
			'attributes.update' 		=> 'Cookbook\Eav\Commands\Attributes\AttributeUpdateCommand',
			'attributes.delete' 		=> 'Cookbook\Eav\Commands\Attributes\AttributeDeleteCommand',
			'attributes.fetch' 			=> 'Cookbook\Eav\Commands\Attributes\AttributeFetchCommand',
			'attributes.get' 			=> 'Cookbook\Eav\Commands\Attributes\AttributeGetCommand',

			// Attribute sets
			'attribute-sets.create' 	=> 'Cookbook\Eav\Commands\AttributeSets\AttributeSetCreateCommand',
			'attribute-sets.update' 	=> 'Cookbook\Eav\Commands\AttributeSets\AttributeSetUpdateCommand',
			'attribute-sets.delete' 	=> 'Cookbook\Eav\Commands\AttributeSets\AttributeSetDeleteCommand',
			'attribute-sets.fetch' 		=> 'Cookbook\Eav\Commands\AttributeSets\AttributeSetFetchCommand',
			'attribute-sets.get' 		=> 'Cookbook\Eav\Commands\AttributeSets\AttributeSetGetCommand',

			// Entity types
			'entity-types.create' 		=> 'Cookbook\Eav\Commands\EntityTypes\EntityTypeCreateCommand',
			'entity-types.update' 		=> 'Cookbook\Eav\Commands\EntityTypes\EntityTypeUpdateCommand',
			'entity-types.delete' 		=> 'Cookbook\Eav\Commands\EntityTypes\EntityTypeDeleteCommand',
			'entity-types.fetch' 		=> 'Cookbook\Eav\Commands\EntityTypes\EntityTypeFetchCommand',
			'entity-types.get' 			=> 'Cookbook\Eav\Commands\EntityTypes\EntityTypeGetCommand'
		];

		$this->app->make('Cookbook\Api\Dispatcher')->map($mappings, 'commands');

		$mappings = [
			'attribute' 				=> 'attributes.get',
			'attribute-set' 			=> 'attribute-sets.get',
			'entity-type' 				=> 'entity-types.get'
		];

		$this->app->make('Cookbook\Api\Dispatcher')->map($mappings, 'resolvers');
	}

}