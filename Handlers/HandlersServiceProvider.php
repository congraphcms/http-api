<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Handlers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

/**
 * HandlersServiceProvider service provider for API handlers
 * 
 * It will register all API handlers to app container
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class HandlersServiceProvider extends EventServiceProvider {

	/**
	 * The event listener mappings for package.
	 *
	 * @var array
	 */
	protected $listen = [
		'Cookbook.api.after.attribute-sets.get' => [
			'Cookbook\Api\Handlers\Events\IncludeAttributesHandler',
		],
	];

	/**
	 * The subscriber classes to register.
	 *
	 * @var array
	 */
	protected $subscribe = [];


	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot(DispatcherContract $events) {
		parent::boot($events);
	}


	/**
	 * Register
	 * 
	 * @return void
	 */
	public function register() {

	}


	/**
	 * Registers Command Handlers
	 *
	 * @return void
	 */
	public function registerCommandHandlers() {

	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [

		];
	}
}