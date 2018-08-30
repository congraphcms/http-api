<?php
/*
 * This file is part of the congraph/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Api;

use Closure;
use Congraph\Core\Repositories\Collection;
use Congraph\Core\Repositories\DataTransferObject;
use Congraph\Core\Repositories\Model;
use Exception;
use ReflectionClass;
use Illuminate\Support\Facades\Request;

/**
 * API Dispatcher class
 *
 * Dispatches API calls
 *
 * @uses  		Illuminate\Routing\Controller
 * @uses  		Illuminate\Contracts\Bus\Dispatcher
 * @uses  		Illuminate\Contracts\Routing\ResponseFactory
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/eav
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class Linker
{


	public static function getLinks(DataTransferObject $result, $endpoint)
	{
		$links = [];
		if($result instanceof Model)
		{
			$url = app('Dingo\Api\Routing\UrlGenerator')
					->version('v1')
					->route(self::getRoutePrefix() . $endpoint . '.fetch', ['id' => $result->id]);
			$query = [];
			if($result->getMeta('include'))
			{
				$query['include'] = $result->getMeta('include');
			}
			if($result->getMeta('locale'))
			{
				$query['locale'] = $result->getMeta('locale');
			}
			if($result->getMeta('status'))
			{
				$query['status'] = $result->getMeta('status');
			}

			if( ! empty($query) )
			{
				$qString = http_build_query($query);
				$url .= '?' . $qString;
			}
			$links['self'] = $url;
		}
		else if($result instanceof Collection)
		{
			$baseUrl = app('Dingo\Api\Routing\UrlGenerator')
					->version('v1')
					->route('CB.' . $endpoint . '.get');
			$query = [];
			if($result->getMeta('filter'))
			{
				$query['filter'] = $result->getMeta('filter');
			}
			if($result->getMeta('offset'))
			{
				$query['offset'] = $result->getMeta('offset');
			}
			if($result->getMeta('limit'))
			{
				$query['limit'] = $result->getMeta('limit');
			}
			if($result->getMeta('sort'))
			{
				$query['sort'] = $result->getMeta('sort');
			}
			if($result->getMeta('include'))
			{
				$query['include'] = $result->getMeta('include');
			}
			if($result->getMeta('locale'))
			{
				$query['locale'] = $result->getMeta('locale');
			}
			if($result->getMeta('status'))
			{
				$query['status'] = $result->getMeta('status');
			}

			if( ! empty($query) )
			{
				$qString = http_build_query($query);
				$url = $baseUrl . '?' . $qString;
			}
			else
			{
				$url = $baseUrl;
			}
			$links['self'] = $url;

			if(! empty($query['offset']))
			{
				$prevOffset = 0;
				$prevLimit = $query['offset'];
				if( ! empty($query['limit']) && $query['limit'] > 0)
				{
					$prevOffset = max($query['offset'] - $query['limit'], 0);
					$prevLimit = min($query['limit'], $query['offset']);
				}
				$prevQuery = $query;
				$prevQuery['offset'] = $prevOffset;
				$prevQuery['limit'] = $prevLimit;
				$qString = http_build_query($prevQuery);
				$prevUrl = $baseUrl . '?' . $qString;
				$links['prev'] = $prevUrl;
			}

			if( ! empty($query['limit']) && ( (empty($query['offset']) && $query['limit'] < $result->getMeta('total') ) || ( ! empty($query['offset']) && $query['offset'] + $query['limit'] < $result->getMeta('total') )) )
			{
				$nextOffset = (isset($query['offset']))? $query['offset'] + $query['limit'] : 0 + $query['limit'];
				$nextQuery = $query;
				$nextQuery['offset'] = $nextOffset;
				$qString = http_build_query($nextQuery);
				$nextUrl = $baseUrl . '?' . $qString;
				$links['next'] = $nextUrl;
			}
		}

		return $links;
	}

	public static function addLinks($array, $key, $value)
	{
		if(self::isModel($value))
		{
			$value = self::addLink($value);
		}

		return $value;
	}

	public static function isModel($value)
	{
		if(is_array($value) && array_key_exists('id', $value) && array_key_exists('type', $value))
		{
			return true;
		}

		return false;
	}

	public static function addLink($value)
	{
		$url = app('Dingo\Api\Routing\UrlGenerator')
					->version('v1')
					->route(self::getRoutePrefix() . $value['type'] . '.fetch', ['id' => $value['id']]);
		$links = [
			'self' => $url
		];
		$value['links'] = $links;
		return $value;
	}

	protected static function getRoutePrefix() {
		$routePrefix = 'CB.';
		if(Request::segment(2) == 'delivery'){
			$routePrefix .= 'delivery.';
		}

		return $routePrefix;
	}
}
