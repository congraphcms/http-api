<?php
/*
 * This file is part of the congraph/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Api;

use Closure;
use Exception;
use ReflectionClass;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Bus\Dispatcher as CommandDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

use Congraph\Core\Exceptions\Exception as CongraphException;
use Congraph\Core\Traits\MapperTrait;

/**
 * API Dispatcher class
 * 
 * Dispatches API calls
 * 
 * @uses  		Illuminate\Routing\Controller
 * @uses  		Illuminate\Contracts\Bus\Dispatcher
 * @uses  		Illuminate\Contracts\Routing\ResponseFactory
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/eav
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class Dispatcher
{
	use MapperTrait;

	/**
	 * Bus Dispatcher for commands
	 * 
	 * @var Illuminate\Contracts\Bus\Dispatcher
	 */
	public $bus;

	/**
	 * Dispatcher for events
	 * 
	 * @var Illuminate\Contracts\Bus\Dispatcher
	 */
	public $event;

	/**
	 * ID from url
	 * 
	 * @var mixed
	 */
	public $id = null;
	

	public function __construct(CommandDispatcher $bus, EventDispatcher $event)
	{
		$this->bus = $bus;
		$this->event = $event;
	}

	/**
	 * Dispatch registered command for endpoint using 
	 * Bus Dispatcher, fire appropriate events
	 * and handle the result as JSON response for API
	 *
	 * @param  string  					$endpoint
	 * @param  Illuminate\Http\Request  $request
	 * @param  int  					$id
	 * @param  Closure  				$afterResolving
	 * 
	 * @return mixed
	 */
	public function call($endpoint, Request $request, $id = null, Closure $afterResolving = null, $internal = false)
	{
		try
		{
			$beforeArgs = [$request, $id];
			$this->fireEvents($endpoint, 'before', $beforeArgs);

			// dispatch the command
			$result = $this->dispatchCommand($endpoint, [$request->all(), $id], $afterResolving);

			$result = ['data' => $result];

			$afterArgs = [&$result, $request, $id];

			$this->fireEvents($endpoint, 'after', $afterArgs);
		}
		catch(Exception $e)
		{
			if($internal)
			{
				throw $e;
			}

			return $this->handleException($e);
		}

		$this->handleInclude($result, $request);

		if($internal)
		{
			return $result;
		}
		
		// if we are creating objects, return 201 CREATED staus
		if (strpos($endpoint, 'create') !== FALSE)
		{
			return $this->createResponse($result, 201);
		}

		// return the handler result
		return $this->createResponse($result);
	}

	protected function handleInclude(&$result, Request $request)
	{
		// if data is empty there will be no includes
		if( empty($result['data']) )
		{
			return;
		}

		// get data
		$data = $result['data'];

		// if data is a single object put it in array
		if( ! is_array($data) )
		{
			$data = [$data];
		}

		// take include params / comma(,) separated values turn to array
		$params = $request->input('include');
		$params = ( is_array($params) ) ? $params : explode(',', $params);

		$sortedParams = [];

		// sort include params
		foreach ($params as $param)
		{
			$param = trim($param);
			
			$includes = explode('.', $param);

			if( empty($includes[0]) )
			{
				continue;
			}

			if( ! array_key_exists($includes[0], $sortedParams) )
			{
				$sortedParams[$includes[0]] = [];
			}

			$newInclude = '';
			if(count($includes) > 1)
			{
				for ($i=1; $i < count($includes); $i++)
				{ 
					$newInclude .= (empty($include))? $includes[$i] : '.' . $includes[$i];
				}
			}

			if( ! empty($newInclude) )
			{
				$sortedParams[$includes[0]][] = $newInclude;
			}
		}

		// list of different objects and their IDs
		$toBeIncluded = [];

		// go through sorted params
		foreach ($sortedParams as $currentInclude => $newIncludeParams)
		{

			foreach ($data as $object)
			{
				if( ! is_object($object) || empty($object->{$currentInclude}) )
				{
					continue;
				}

				$includeObjParams = $object->{$currentInclude};

				if( ! is_array($includeObjParams) )
				{
					$includeObjParams = [$includeObjParams];
				}

				foreach ($includeObjParams as $includeObj)
				{
					if( empty($includeObj) || ! is_object($includeObj) || empty($includeObj->id) || empty($includeObj->type) )
					{
						continue;
					}

					if( ! array_key_exists($currentInclude, $toBeIncluded) )
					{
						$toBeIncluded[$currentInclude] = [];
					}

					if( ! array_key_exists($includeObj->type, $toBeIncluded[$currentInclude]) )
					{
						$toBeIncluded[$currentInclude][$includeObj->type] = [];
					}

					$toBeIncluded[$currentInclude][$includeObj->type][] = $includeObj->id;
				}
			}
		}

		if( empty($toBeIncluded) )
		{
			return;
		}

		$included = [];

		foreach ($toBeIncluded as $currentInclude => $types)
		{
			foreach ($types as $type => $ids)
			{
				$resolveEndpoints = $this->getMappings($type, 'resolvers');

				if( empty($resolveEndpoints) )
				{
					throw new Exception('No resolver registered for object type: ' . $type);
				}

				$endpoint = $resolveEndpoints[0];

				$request = new Request(
					array( 
						'filter' => [ 
							'id' => ['in'=>$ids] 
						], 
						'include' => $sortedParams[$currentInclude]
					)
				);

				$includeResult = $this->call($endpoint, $request, null, null, true);

				if( ! empty($includeResult['data']) && is_array($includeResult['data']) )
				{
					// $included = array_merge($included, $includeResult['data']);
					// $included = array_unique($included);
					foreach ($includeResult['data'] as $object)
					{
						$duplicates = array_filter($included, function($item) use($object){
							if($item->id == $object->id && $item->type == $object->type)
							{
								return true;
							}

							return false;
						});

						if(empty($duplicates))
						{
							$included[] = $object;
						}
					}
				}

				if( ! empty($includeResult['include']) && is_array($includeResult['include']) )
				{
					// $included = array_merge($included, $includeResult['include']);
					// $included = array_unique($included);
					foreach ($includeResult['include'] as $object)
					{
						$duplicates = array_filter($included, function($item) use($object){
							if($item->id == $object->id && $item->type == $object->type)
							{
								return true;
							}

							return false;
						});

						if(empty($duplicates))
						{
							$included[] = $object;
						}
					}
				}
			}
		}

		$result['include'] = $included;
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
		$errors = $e->toArray();

		$responseCode = $this->getResponseCodeFromErrors($errors);

		return $this->createResponse(['errors' => $errors], $responseCode);
	}

	protected function handleGenericException(Exception $e)
	{
		$message = $e->getMessage();
		$code = $e->getCode();
		$status = 500;

		$error = [
			'status' 	=> $status,
			'code'	 	=> $code,
			'message' 	=> $message,
			'pointer' 	=> '/'
		];

		$errors = [$error];

		return $this->createResponse(['errors' => $errors], $status);
	}

	protected function getResponseCodeFromErrors(array $errors)
	{
		$statusDetail = 0;
		$statusGeneral = 0;
		foreach ($errors as $error) {
			// if it's first error set values and move on
			if( $statusDetail == 0 )
			{
				$statusGeneral = $statusDetail = $error['status'];
				continue;
			}

			// get http status code group
			$statusGroup = intval(floor($error['status'] / 100) * 100);

			// check if group is lower then previous error
			// if it is break
			if( $statusGroup < intval(floor($statusGeneral / 100) * 100) )
			{
				continue;
			}
			// check if errors are different
			// if they are return general http code (400, 500...)
			if($statusDetail != $error['status'])
			{
				$statusGeneral = $statusGroup;
			}
		}

		return $statusGeneral;
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
	public function createResponse($data = [], $code = 200, $headers = [])
	{
		$response = new Response(json_encode($data), $code);

		$response->header('Content-Type', 'application/vnd.congraph.api+json');

		if( ! empty($headers) && is_array($headers) )
		{
			foreach ($headers as $key => $value) {
				$response->header($key, $value, true);
			}
		}

		return $response;
	}

	/**
	 * Get command registered for given endpoint
	 *
	 * @param  string  $endpoint
	 * 
	 * @return object
	 */
	protected function dispatchCommand($endpoint, $args, $afterResolving)
	{
		$commandClasses = $this->getMappings($endpoint, 'commands');

		if( empty($commandClasses) )
		{
			throw new Exception('No command registered for API endpoint: ' . $endpoint);
		}

		$reflectionClass = new ReflectionClass($commandClasses[0]);
		
		$command = $reflectionClass->newInstanceArgs($args);

		return $this->bus->dispatch($command, $afterResolving);
	}

	/**
	 * Fire registered before command events
	 *
	 * @param  mixed  $command
	 * 
	 * @return void
	 */
	protected function fireEvents($endpoint, $beforeOrAfter, &$args)
	{
		$eventName = 'Congraph.api.' . $beforeOrAfter . '.' . $endpoint;

		$this->event->fire($eventName, $args);
	}

}