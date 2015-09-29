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

use Cookbook\Api\Events\AttributeSets\AfterAttributeSetGetEvent;


/**
 * IncludeAttributesHandler  class
 * 
 * Handling inclusion of attributes on different requests
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class IncludeAttributesHandler
{


	/**
	 * Handle Inclusion of attributes
	 * 
	 * @param $result
	 * @param $request
	 * @param $id
	 * 
	 * @return void
	 */
	public function handle(&$result, $request, $id)
	{
		$params = $request->input('include');
		$params = ( is_array($params) ) ? $params : explode(',', $params);

		$has_include = false;
		$attributesInclude = [];

		foreach ($params as $param) {
			$includes = explode('.', $param);

			if($includes[0] == 'attributes')
			{
				$has_include = true;

				if(count($includes) > 1)
				{
					$newInclude = '';
					for ($i=1; $i < count($includes); $i++)
					{ 
						$newInclude .= (empty($include))? $includes[0] : '.' . $includes[0];
					}
					$attributesInclude[] = $newInclude;
				}
				
			}
		}

		if( ! $has_include )
		{
			return;
		}

		$attributeIds = [];
		if( is_array($result['data']) && ! empty($result['data']) )
		{
			foreach ($result['data'] as $object) {
				if( ! empty($object->attributes) )
				{
					foreach ($object->attributes as $attribute) {
						$attributeIds[] = $attribute->id;
					}
				}
			}
		}
		else
		{
			if( ! empty($result['data']->attributes) )
			{
				foreach ($result['data']->attributes as $attribute) {
					$attributeIds[] = $attribute->id;
				}
			}
		}

		
		if( empty($attributeIds) )
		{
			return;
		}

		
	}
}