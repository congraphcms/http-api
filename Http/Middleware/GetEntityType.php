<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Http\Middleware;

use Closure;
use Cookbook\Contracts\Eav\EntityTypeRepositoryContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * GetEntityType Middleware class
 * 
 * Checks if type exists and adds it to Request
 * 
 * @author      Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright   Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package     cookbook/eav
 * @since       0.1.0-alpha
 * @version     0.1.0-alpha
 */
class GetEntityType
{

	/**
	 * Repository for entity types
	 * 
	 * @var Cookbook\Contracts\Eav\EntityTypeRepositoryContract
	 */
	protected $entityTypeRepository;

	/**
	 * Create new GetEntityType
	 * 
	 * @param \Cookbook\Contracts\Eav\EntityTypeRepositoryContract $repository
	 * 
	 * @return void
	 */
	public function __construct(EntityTypeRepositoryContract $entityTypeRepository)
	{
		$this->entityTypeRepository = $entityTypeRepository;
	}

	/**
	 * Check if entity type in route exist and add it to Request
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$type = $request->route('type');
		$entityTypes = $this->entityTypeRepository->get(['endpoint' => $type]);
		
		if( ! empty($entityTypes) && !empty($entityTypes->toArray()))
		{
			$entityType = $entityTypes[0];
			$filter = $request->input('filter', []);
			$filter = array_merge_recursive($filter, ['entity_type' => $entityType->code]);
			$request->merge(['filter' => $filter]);

			return $next($request);
		}
		
		throw new NotFoundHttpException();
	}
}
