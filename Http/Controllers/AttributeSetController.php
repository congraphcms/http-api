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

/**
 * AttributeSetController class
 *
 * RESTful Controller for attribute resource
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
		return $this->api->call( 'attribute-sets.get', $this->request );
	}

	public function show($id)
	{
		$this->id = $id;

		return $this->api->call( 'attribute-sets.fetch', $this->request, $id );
	}

	public function store()
	{
		return $this->api->call( 'attribute-sets.create', $this->request );
	}

	public function update($id)
	{
		$this->id = $id;

		return $this->api->call( 'attribute-sets.update', $this->request, $id );
	}

	public function destroy($id)
	{
		$this->id = $id;

		return $this->api->call( 'attribute-sets.delete', $this->request, $id );
	}
}
