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

use Cookbook\Eav\Commands\EntityTypes\EntityTypeGetCommand;
use Cookbook\Eav\Commands\EntityTypes\EntityTypeFetchCommand;
use Cookbook\Eav\Commands\EntityTypes\EntityTypeCreateCommand;
use Cookbook\Eav\Commands\EntityTypes\EntityTypeUpdateCommand;
use Cookbook\Eav\Commands\EntityTypes\EntityTypeDeleteCommand;

use Dingo\Api\Http\Response;

/**
 * EntityTypeController class
 *
 * RESTful Controller for entity type resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class EntityTypeController extends ApiController
{
	public function index()
	{
		$command = new EntityTypeGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function show($id)
	{
		$command = new EntityTypeFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('entity-types.fetch', [$id]);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function store()
	{
		$command = new EntityTypeCreateCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('entity-types.fetch', [$result->id]);

		$response = new Response($result->toArray(false, false), 201);

		return $response;
	}

	public function update($id)
	{
		$command = new EntityTypeUpdateCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('entity-types.fetch', [$id]);
		$response = new Response($result->toArray(false, false), 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new EntityTypeDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
