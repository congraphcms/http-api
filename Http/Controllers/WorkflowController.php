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

use Cookbook\Workflows\Commands\Workflows\WorkflowGetCommand;
use Cookbook\Workflows\Commands\Workflows\WorkflowFetchCommand;
use Cookbook\Workflows\Commands\Workflows\WorkflowCreateCommand;
use Cookbook\Workflows\Commands\Workflows\WorkflowUpdateCommand;
use Cookbook\Workflows\Commands\Workflows\WorkflowDeleteCommand;

use Dingo\Api\Http\Response;


/**
 * WorkflowController class
 *
 * RESTful Controller for Workflow resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class WorkflowController extends ApiController
{
	public function index()
	{
		$command = new WorkflowGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function show($id)
	{
		$command = new WorkflowFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('workflows.fetch', [$id]);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function store()
	{
		$command = new WorkflowCreateCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('workflows.fetch', [$result->id]);

		$response = new Response($result->toArray(false, false), 201);

		return $response;
	}

	public function update($id)
	{
		$command = new WorkflowUpdateCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('workflows.fetch', [$id]);
		$response = new Response($result->toArray(false, false), 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new WorkflowDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
