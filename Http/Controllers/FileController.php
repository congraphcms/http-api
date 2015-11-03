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

use Cookbook\Filesystem\Commands\Files\FileGetCommand;
use Cookbook\Filesystem\Commands\Files\FileFetchCommand;
use Cookbook\Filesystem\Commands\Files\FileCreateCommand;
use Cookbook\Filesystem\Commands\Files\FileUpdateCommand;
use Cookbook\Filesystem\Commands\Files\FileDeleteCommand;

use Dingo\Api\Http\Response;


/**
 * FileController class
 *
 * RESTful Controller for file resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class FileController extends ApiController
{
	public function index()
	{
		$command = new FileGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function show($id)
	{
		$command = new FileFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('files.fetch', [$id]);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		$response->header('Location', $link);
		return $response;
	}

	public function store()
	{
		$command = new FileCreateCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('files.fetch', [$result->id]);

		$response = new Response($result->toArray(false, false), 201);
		$response->header('Location', $link);

		return $response;
	}

	public function update($id)
	{
		$command = new FileUpdateCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('files.fetch', [$id]);
		$response = new Response($result->toArray(false, false), 200);
		$response->header('Location', $link);
		return $response;
	}

	public function destroy($id)
	{
		$command = new FileDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
