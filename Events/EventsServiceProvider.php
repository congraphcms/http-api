<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Events;

use Illuminate\Support\ServiceProvider;
use Cookbook\Api\Http\Controllers\ApiController;

/**
 * EventsServiceProvider service provider for API events
 * 
 * It will register all events for API
 * 
 * @uses   		Illuminate\Support\ServiceProvider
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class EventsServiceProvider extends ServiceProvider {

	/**
	 * Boot
	 * 
	 * @return void
	 */
	public function boot() {
		$this->mapApiEvents();
	}


	/**
	 * Register
	 * 
	 * @return void
	 */
	public function register() {
		
	}

	/**
	 * Maps API Events
	 *
	 * @return void
	 */
	public function mapApiEvents() {
		
		$mappings = [

			'Cookbook\Api\Http\Controllers\AttributeSetController' => [
				'index' => [
					// 'before' 	=> 'Cookbook\Eav\Events\AttributeSets\BeforeAttributeSetGet',
					'after' 	=> 'Cookbook\Api\Events\AttributeSets\AfterAttributeSetGetEvent'
				]
				
			]
			// Attributes
			// 'Cookbook\Eav\Commands\Attributes\AttributeCreateCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\Attributes\BeforeAttributeCreate',
			// 	'after' 	=> 'Cookbook\Eav\Events\Attributes\AfterAttributeCreate'
			// ],
			// 'Cookbook\Eav\Commands\Attributes\AttributeUpdateCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\Attributes\BeforeAttributeUpdate',
			// 	'after' 	=> 'Cookbook\Eav\Events\Attributes\AfterAttributeUpdate'
			// ],
			// 'Cookbook\Eav\Commands\Attributes\AttributeDeleteCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\Attributes\BeforeAttributeDelete',
			// 	'after' 	=> 'Cookbook\Eav\Events\Attributes\AfterAttributeDelete'
			// ],
			// 'Cookbook\Eav\Commands\Attributes\AttributeFetchCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\Attributes\BeforeAttributeFetch',
			// 	'after' 	=> 'Cookbook\Eav\Events\Attributes\AfterAttributeFetch'
			// ],
			// 'Cookbook\Eav\Commands\Attributes\AttributeGetCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\Attributes\BeforeAttributeGet',
			// 	'after' 	=> 'Cookbook\Eav\Events\Attributes\AfterAttributeGet'
			// ],

			// // Attribute sets
			// 'Cookbook\Eav\Commands\AttributeSets\AttributeSetCreateCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\AttributeSets\BeforeAttributeSetCreate',
			// 	'after' 	=> 'Cookbook\Eav\Events\AttributeSets\AfterAttributeSetCreate'
			// ],
			// 'Cookbook\Eav\Commands\AttributeSets\AttributeSetUpdateCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\AttributeSets\BeforeAttributeSetUpdate',
			// 	'after' 	=> 'Cookbook\Eav\Events\AttributeSets\AfterAttributeSetUpdate'
			// ],
			// 'Cookbook\Eav\Commands\AttributeSets\AttributeSetDeleteCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\AttributeSets\BeforeAttributeSetDelete',
			// 	'after' 	=> 'Cookbook\Eav\Events\AttributeSets\AfterAttributeSetDelete'
			// ],
			// 'Cookbook\Eav\Commands\AttributeSets\AttributeSetFetchCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\AttributeSets\BeforeAttributeSetFetch',
			// 	'after' 	=> 'Cookbook\Eav\Events\AttributeSets\AfterAttributeSetFetch'
			// ],
			// 'Cookbook\Eav\Commands\AttributeSets\AttributeSetGetCommand' => [
			// 	'before' 	=> 'Cookbook\Eav\Events\AttributeSets\BeforeAttributeSetGet',
			// 	'after' 	=> 'Cookbook\Eav\Events\AttributeSets\AfterAttributeSetGet'
			// ],
		];

		ApiController::map($mappings, 'events');
	}

}