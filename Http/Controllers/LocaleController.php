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
use Cookbook\Locales\Commands\Locales\LocaleCreateCommand;
use Cookbook\Locales\Commands\Locales\LocaleDeleteCommand;
use Cookbook\Locales\Commands\Locales\LocaleFetchCommand;
use Cookbook\Locales\Commands\Locales\LocaleGetCommand;
use Cookbook\Locales\Commands\Locales\LocaleUpdateCommand;
use Dingo\Api\Http\Response;


/**
 * LocaleController class
 *
 * RESTful Controller for locale resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleController extends ApiController
{
	public function index()
	{
		$command = new LocaleGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, $this->nestedInclude, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function show($id)
	{
		$command = new LocaleFetchCommand($this->request->all(), $id);
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
		$command = new LocaleCreateCommand($params);
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
		$command = new LocaleUpdateCommand($params, $id);
		$result = $this->dispatchCommand($command);
		$links = Linker::getLinks($result);
		$parsedResult = $result->toArray($this->includeMeta, false, [Linker::class, 'addLinks']);
		$parsedResult['links'] = $links;
		$response = new Response($parsedResult, 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new LocaleDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
