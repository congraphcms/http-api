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

use Closure;
use Cookbook\Core\Bus\CommandDispatcher;
use Cookbook\Core\Exceptions\BadRequestException;
use Cookbook\Core\Exceptions\Exception as CookbookException;
use Cookbook\Core\Exceptions\NotFoundException;
use Cookbook\Core\Exceptions\ValidationException;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


// use Cookbook\Api\Dispatcher as ApiDispatcher;

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
	use Helpers;
	/**
	 * Command Bus Dispatcher
	 * 
	 * @var Illuminate\Contracts\Bus\Dispatcher
	 */
	public $bus;

	/**
	 * Current request
	 * 
	 * @var Illuminate\Http\Request
	 */
	public $request;

	/**
	 * Should meta data be included in result
	 * 
	 * @var boolean
	 */
	public $includeMeta;

	/**
	 * Should included object be nested in result or 
	 * separated in their own array
	 * 
	 * @var boolean
	 */
	public $nestedInclude;
	

	public function __construct(CommandDispatcher $bus, Request $request)
	{
		$this->bus = $bus;
		$this->request = $request;
		$this->includeMeta = Config::get('cookbook.include_metadata');
		$this->nestedInclude = Config::get('cookbook.nested_include');
	}

	/**
	 * Dispatch a command and handle exceptions
	 *
	 * @param  string  $endpoint
	 * 
	 * @return object
	 */
	protected function dispatchCommand($command, Closure $afterResolving = null)
	{
		try
		{
			return $this->bus->dispatch($command, $afterResolving);
		}
		catch (Exception $e)
		{
			$this->handleException($e);
		}
	}

	protected function handleException(Exception $e)
	{
		// if it's a cookbook exception, 
		// use it's toArray function to ger all errors
		if( $e instanceOf CookbookException)
		{
			$this->handleCookbookException($e);
		}

		// if it's some other exception throw that exception
		throw $e;
	}

	protected function handleCookbookException(CookbookException $e)
	{

		$errors = $e->getErrors();
		$message = $e->getMessage();

		switch (true)
		{
			case $e instanceof ValidationException:
				throw new ResourceException($message, $errors);
				break;
			case $e instanceof NotFoundException:
				throw new NotFoundHttpException($message);
				break;
			case $e instanceof BadRequestException:
				throw new BadRequestHttpException($message);
				break;
			default:
				throw $e;
				break;
		}
	}

}