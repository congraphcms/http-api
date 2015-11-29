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

use Cookbook\Workflows\Commands\WorkflowPoints\WorkflowPointGetCommand;
use Cookbook\Workflows\Commands\WorkflowPoints\WorkflowPointFetchCommand;
use Cookbook\Workflows\Commands\WorkflowPoints\WorkflowPointCreateCommand;
use Cookbook\Workflows\Commands\WorkflowPoints\WorkflowPointUpdateCommand;
use Cookbook\Workflows\Commands\WorkflowPoints\WorkflowPointDeleteCommand;

use Dingo\Api\Http\Response;


/**
 * WorkflowPointController class
 *
 * RESTful Controller for Workflow Point resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class WorkflowPointController extends ApiController
{
	public function index()
	{
		$command = new WorkflowPointGetCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function show($id)
	{
		$command = new WorkflowPointFetchCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('workflow-points.fetch', [$id]);
		$response = new Response($result->toArray($this->includeMeta, $this->nestedInclude), 200);
		return $response;
	}

	public function store()
	{
		$command = new WorkflowPointCreateCommand($this->request->all());
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('workflow-points.fetch', [$result->id]);

		$response = new Response($result->toArray(false, false), 201);

		return $response;
	}

	public function update($id)
	{
		$command = new WorkflowPointUpdateCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		$link = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('workflow-points.fetch', [$id]);
		$response = new Response($result->toArray(false, false), 200);
		return $response;
	}

	public function destroy($id)
	{
		$command = new WorkflowPointDeleteCommand($this->request->all(), $id);
		$result = $this->dispatchCommand($command);
		return $this->response->noContent();
	}
}
