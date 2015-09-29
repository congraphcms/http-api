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

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Cookbook\Api\Dispatcher as ApiDispatcher;
use Cookbook\Core\Exceptions\Exception as CookbookException;
use Cookbook\Core\Traits\MapperTrait;

/**
 * BaseApiController class
 * 
 * Controller for handling API requests 
 * 
 * @uses  		Illuminate\Routing\Controller
 * @uses  		Illuminate\Contracts\Bus\Dispatcher
 * @uses  		Illuminate\Contracts\Routing\ResponseFactory
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/eav
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ApiController extends Controller
{
	use MapperTrait;

	/**
	 * API Dispatcher for api calls
	 * 
	 * @var Illuminate\Contracts\Bus\Dispatcher
	 */
	public $api;

	/**
	 * Current request
	 * 
	 * @var Illuminate\Http\Request
	 */
	public $request;
	

	public function __construct(ApiDispatcher $api, Request $request)
	{
		$this->api = $api;
		$this->request = $request;
	}

}