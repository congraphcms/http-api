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

use Closure;
use Congraph\Core\Bus\CommandDispatcher;
use Congraph\Core\Exceptions\BadRequestException;
use Congraph\Core\Exceptions\Exception as CongraphException;
use Congraph\Core\Exceptions\NotFoundException;
use Congraph\Core\Exceptions\ValidationException;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ApiController class
 * 
 * Controller for handling API requests 
 * 
 * @uses  		Illuminate\Routing\Controller
 * @uses  		Congraph\Core\Bus\CommandDispatcher
 * @uses  		Illuminate\Http\Request
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/eav
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ApiController extends Controller
{
	/**
	 * Dingo API Controller helpers
	 */
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
	


	/**
	 * Constructor
	 * 
	 * @param \Congraph\Core\Bus\CommandDispatcher  $bus
	 * @param \Illuminate\Http\Request  			$request
	 */
	public function __construct(CommandDispatcher $bus, Request $request)
	{
		$this->bus = $bus;
		$this->request = $request;
		$this->includeMeta = Config::get('cb.api.include_metadata');
		$this->nestedInclude = Config::get('cb.api.nested_include');
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

	/**
	 * Handle Congraph exceptions
	 * and convert them to appropriate HTTP exceptions
	 * for Dingo API
	 * 
	 * @param \Exception $e
	 * 
	 * @throws  $e
	 */
	protected function handleException(Exception $e)
	{
		// if it's a congraph exception, 
		// use it's toArray function to ger all errors
		if( $e instanceOf CongraphException)
		{
			$this->handleCongraphException($e);
		}

		// if it's some other exception don't handle it
		throw $e;
	}

	/**
	 * Matches correct HTTP exception
	 * 
	 * @param \Congraph\Core\Exceptions\Exception  $e
	 */
	protected function handleCongraphException(CongraphException $e)
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