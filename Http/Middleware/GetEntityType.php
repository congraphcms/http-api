<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Http\Middleware;

use Closure;

/**
 * GetEntityType Middleware class
 * 
 * Checks if type exists and adds it to Request
 * 
 * @author      Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright   Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package     cookbook/eav
 * @since       0.1.0-alpha
 * @version     0.1.0-alpha
 */
class GetEntityType
{

	/**
	 * Check if entity type in route exist and add it to Request
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$type = $request->route('type');
		if( ! empty($type) )
		{
			$request->merge(['type' => $type]);
		}
		
		return $next($request);
	}
}
