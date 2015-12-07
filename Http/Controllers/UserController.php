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
use Cookbook\OAuth\Commands\Users\UserCreateCommand;
use Cookbook\OAuth\Commands\Users\UserDeleteCommand;
use Cookbook\OAuth\Commands\Users\UserFetchCommand;
use Cookbook\OAuth\Commands\Users\UserGetCommand;
use Cookbook\OAuth\Commands\Users\UserUpdateCommand;
use Dingo\Api\Http\Response;


/**
 * UserController class
 *
 * RESTful Controller for user resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class UserController extends ApiController
{
	public function index()
	{
		$command = new UserGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function show($id)
	{
		$command = new UserFetchCommand($this->request->all(), $id);
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
		$command = new UserCreateCommand($params);
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
		$command = new UserUpdateCommand($params, $id);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, false, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new UserDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
