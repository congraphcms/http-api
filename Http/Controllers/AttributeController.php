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
use Cookbook\Eav\Commands\Attributes\AttributeCreateCommand;
use Cookbook\Eav\Commands\Attributes\AttributeDeleteCommand;
use Cookbook\Eav\Commands\Attributes\AttributeFetchCommand;
use Cookbook\Eav\Commands\Attributes\AttributeGetCommand;
use Cookbook\Eav\Commands\Attributes\AttributeUpdateCommand;
use Dingo\Api\Http\Response;


/**
 * AttributeController class
 *
 * RESTful Controller for attribute resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class AttributeController extends ApiController
{
	public function index()
	{
		$command = new AttributeGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function show($id)
	{
		$command = new AttributeFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function store()
	{
		$params = [];
		if($this->request->input('data'))
		{
			$params = $this->request->input('data');
		}
		else
		{
			$params = $this->request->all();
		}
		$command = new AttributeCreateCommand($params);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, false, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 201);

		return $response;
	}

	public function update($id)
	{
		$params = [];
		if($this->request->input('data'))
		{
			$params = $this->request->input('data');
		}
		else
		{
			$params = $this->request->all();
		}
		$command = new AttributeUpdateCommand($params, $id);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, false, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new AttributeDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
