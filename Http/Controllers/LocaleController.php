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

use Cookbook\Locales\Commands\Locales\LocaleGetCommand;
use Cookbook\Locales\Commands\Locales\LocaleFetchCommand;
use Cookbook\Locales\Commands\Locales\LocaleCreateCommand;
use Cookbook\Locales\Commands\Locales\LocaleUpdateCommand;
use Cookbook\Locales\Commands\Locales\LocaleDeleteCommand;

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
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function show($id)
	{
		$command = new LocaleFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('locales.fetch', [$id]);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		$response->header('Location', $link);
		return $response;
	}

	public function store()
	{
		$command = new LocaleCreateCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('locales.fetch', [$result->id]);

		$response = new Response($result->toArray(false, false), 201);
		$response->header('Location', $link);

		return $response;
	}

	public function update($id)
	{
		$command = new LocaleUpdateCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('locales.fetch', [$id]);
		$response = new Response($result->toArray(false, false), 200);
		$response->header('Location', $link);
		return $response;
	}

	public function destroy($id)
	{
		$command = new LocaleDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
