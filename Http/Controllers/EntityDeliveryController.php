<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Http\Controllers;

use Cookbook\Api\Linker;
use Cookbook\Eav\Commands\Entities\EntityFetchCommand;
use Cookbook\Eav\Commands\Entities\EntityGetCommand;
use Dingo\Api\Http\Response;


/**
 * EntityDeliveryController class
 *
 * RESTful Controller for every entity resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class EntityDeliveryController extends ApiController
{
	public function index()
	{
		if(Config::get('cb.eav.using_elastic'))
		{
			$command = new \Cookbook\EntityElastic\Commands\Entities\EntityGetCommand($this->request->all());
		}
		else
		{
			$command = new EntityGetCommand($this->request->all());
		}
		
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result, 'entity');
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function show($id)
	{
		if(Config::get('cb.eav.using_elastic'))
		{
			$command = new \Cookbook\EntityElastic\Commands\Entities\EntityFetchCommand($this->request->all(), $id);
		}
		else
		{
			$command = new EntityFetchCommand($this->request->all(), $id);
		}
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result, 'entity');
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}
}
