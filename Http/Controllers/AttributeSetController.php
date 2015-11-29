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

use Cookbook\Eav\Commands\AttributeSets\AttributeSetGetCommand;
use Cookbook\Eav\Commands\AttributeSets\AttributeSetFetchCommand;
use Cookbook\Eav\Commands\AttributeSets\AttributeSetCreateCommand;
use Cookbook\Eav\Commands\AttributeSets\AttributeSetUpdateCommand;
use Cookbook\Eav\Commands\AttributeSets\AttributeSetDeleteCommand;

use Dingo\Api\Http\Response;

/**
 * AttributeSetController class
 *
 * RESTful Controller for attribute set resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class AttributeSetController extends ApiController
{
	public function index()
	{
		$command = new AttributeSetGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function show($id)
	{
		$command = new AttributeSetFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('attribute-sets.fetch', [$id]);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function store()
	{
		$command = new AttributeSetCreateCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('attribute-sets.fetch', [$result->id]);

		$response = new Response($result->toArray(false, false), 201);

		return $response;
	}

	public function update($id)
	{
		$command = new AttributeSetUpdateCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('attribute-sets.fetch', [$id]);
		$response = new Response($result->toArray(false, false), 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new AttributeSetDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
