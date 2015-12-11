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

use Cookbook\Core\Bus\CommandDispatcher;
use Cookbook\Filesystem\Commands\Files\FileServeCommand;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
use Cookbook\Core\Exceptions\Exception as CookbookException;
use Exception;

/**
 * AttributeController class
 *
 * RESTful Controller for attribute resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class FileServeController extends Controller
{

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


	public function __construct(CommandDispatcher $bus, Request $request)
	{
		$this->bus = $bus;
		$this->request = $request;
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

	public function index($fileUrl)
	{
		$version = ($this->request->input('v'))?$this->request->input('v'):null;
		$command = new FileServeCommand($fileUrl, $version);

		try
		{
			$result = $this->dispatchCommand($command);
		}
		catch(Exception $e)
		{
			$this->handleException();
		}
		
		// $mime = Storage::getMimetype($command->url);

		$mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $result);
        // return http response
        $response = new Response($result, 200, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age='.(Config::get('cb.files.cache_lifetime')*60).', public',
            'Etag' => md5($result)
        ));

		return $response;
	}

	protected function handleException(Exception $e)
	{
		// if it's a cookbook exception, 
		// use it's toArray function to ger all errors
		if( $e instanceOf CookbookException )
		{
			return $this->handleCookbookException($e);
		}

		// if it's some other exception return 500 error from exception
		return $this->handleGenericException($e);
	}

	protected function handleCookbookException(CookbookException $e)
	{
		$errors = $e->getErrors();

		$responseCode = $e->getCode();

		return $this->createErrorResponse(['errors' => $errors], $responseCode);
	}

	protected function handleGenericException(Exception $e)
	{
		$message = $e->getMessage();
		$status = 500;

		$error = [
			'status' 	=> $status,
			'code'	 	=> $code,
			'message' 	=> $message,
		];

		$errors = [$error];

		return $this->createErrorResponse(['errors' => $errors], $status);
	}

	/**
	 * Create JSON response for HTTP requests
	 *
	 * @param  array  					$data
	 * @param  int  					$code
	 * @param  array  					$headers
	 *
	 * @return Illuminate\Http\Response
	 *
	 * @todo  Set content-type from config file (allow users to set their vendor in config)
	 */
	public function createErrorResponse($data = [], $code = 200, $headers = [])
	{
		$response = new Response(json_encode($data), $code);

		$response->header('Content-Type', 'application/json');

		if( ! empty($headers) && is_array($headers) )
		{
			foreach ($headers as $key => $value) {
				$response->header($key, $value, true);
			}
		}

		return $response;
	}

}
