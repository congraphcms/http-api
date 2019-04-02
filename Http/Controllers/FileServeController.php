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

use Congraph\Core\Bus\CommandDispatcher;
use Congraph\Filesystem\Commands\Files\FileServeCommand;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
use Congraph\Core\Exceptions\Exception as CongraphException;
use Exception;

/**
 * AttributeController class
 *
 * RESTful Controller for attribute resource
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/api
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
		return $this->bus->dispatch($command, $afterResolving);
	}

	public function index($fileUrl)
	{
		$fileUrl = 'files/' . $fileUrl;
		$version = ($this->request->input('v'))?$this->request->input('v'):null;
		
		$command = new FileServeCommand($fileUrl, $version);

		try
		{
			$result = $this->dispatchCommand($command);
		}
		catch(Exception $e)
		{
			return $this->handleException($e);
		}

        // return http response
        $response = new Response($result['content'], 200, array(
            'Content-Type' => $result['mime_type'],
			'Cache-Control' => 'max-age='.(Config::get('cb.files.cache_lifetime')*60).', public',
			'Last-Modified' => $result['last_modified']->format( 'D, d M Y H:i:s T' ),
            'Etag' => md5(serialize($result))
		));

		return $response;
	}

	protected function handleException(Exception $e)
	{
		// if it's a congraph exception, 
		// use it's toArray function to ger all errors
		if( $e instanceOf CongraphException )
		{
			return $this->handleCongraphException($e);
		}

		// if it's some other exception return 500 error from exception
		return $this->handleGenericException($e);
	}

	protected function handleCongraphException(CongraphException $e)
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
