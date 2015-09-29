<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Handlers\Events;


/**
 * TestEventHandler  class
 * 
 * Handling Cookbook.api.after.attribute-sets.get
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class TestEventHandler
{


	/**
	 * Handle Cookbook.api.after.attribute-sets.get
	 * 
	 * @param  $event
	 * 
	 * @return void
	 */
	public function handle($result, $request, $id)
	{
		
	}
}