<?php
/*
 * This file is part of the congraph/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Api\Http\Controllers;

use Congraph\Api\Linker;
use Congraph\Eav\Commands\AttributeSets\AttributeSetCreateCommand;
use Congraph\Eav\Commands\AttributeSets\AttributeSetDeleteCommand;
use Congraph\Eav\Commands\AttributeSets\AttributeSetFetchCommand;
use Congraph\Eav\Commands\AttributeSets\AttributeSetGetCommand;
use Congraph\Eav\Commands\AttributeSets\AttributeSetUpdateCommand;
use Dingo\Api\Http\Response;

/**
 * AttributeSetController class
 *
 * RESTful Controller for attribute set resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class AttributeSetController extends ApiController
{
	public function index()
	{
		$command = new AttributeSetGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result, 'attribute-set');
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function show($id)
	{
		$command = new AttributeSetFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result, 'attribute-set');
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
		$command = new AttributeSetCreateCommand($params);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result, 'attribute-set');
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
		$command = new AttributeSetUpdateCommand($params, $id);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result, 'attribute-set');
		$parsedResult = $result->toArray($this->includeMeta, false, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new AttributeSetDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
