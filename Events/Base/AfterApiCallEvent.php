<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Events\Base;

use Illuminate\Http\Request;

/**
 * AfterApiCallEvent class
 * 
 * Cookbook After API Call Event
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
abstract class AfterApiCallEvent extends ApiCallEvent
{
	/**
	 * Command that called this event
	 * 
	 * @var mixed
	 */
	public $result;


	/**
	 * CommandEvent constructor
	 * 
	 * @param Illuminate\Http\Request $request
	 */
	public function __construct(Request $request, &$result, $id = null)
	{
		$this->result = &$result;
		parent::__construct($request, $id);
	}
}