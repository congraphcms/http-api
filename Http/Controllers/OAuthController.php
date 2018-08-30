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

use League\OAuth2\Server\Exception\OAuthException;
use Illuminate\Routing\Controller;
use Dingo\Api\Http\Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;


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
class OAuthController extends Controller
{
	public function issue()
	{
		try
		{
			$response = new Response(Authorizer::issueAccessToken(), 200);
			return $response;
		}
		catch(OAuthException $e)
		{
			return $this->handleOAuthException($e);
		}
		
	}

	public function revoke()
	{
		try
		{
			$response = new Response(Authorizer::revokeToken(), 200);
			return $response;
		}
		catch(OAuthException $e)
		{
			return $this->handleOAuthException($e);
		}
	}

	public function owner()
	{
		try
		{
			$owner = Authorizer::getOwner();
			$response = new Response(['data' => $owner->toArray()], 200);
			return $response;
		}
		catch(OAuthException $e)
		{
			return $this->handleOAuthException($e);
		}
	}


	private function handleOAuthException($e)
	{
		return new Response([
	    		'error' => $e->errorType, 
	    		'message' => $e->getMessage(),
	    		'status_code' => $e->httpStatusCode
			], $e->httpStatusCode);
	}
}
