<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
	$api->group(['as' => 'CB::', 'middleware' => 'api.auth', 'providers' => ['oauth']], function($api){

		// Attributes
		$api->group(['prefix' => 'attributes', 'as' => 'attribute::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@show' ] );

		});

		// Attribute Sets
		$api->group(['prefix' => 'attribute-sets', 'as' => 'attribute-set::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@show' ] );

		});

		// Entity Types
		$api->group(['prefix' => 'entity-types', 'as' => 'entity-type::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@show' ] );

		});

		// Entities
		$api->group(['prefix' => 'entities', 'as' => 'entity::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@show' ] );

		});

		// Files
		$api->group(['prefix' => 'files', 'as' => 'file::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@show' ] )->where('id', '[0-9]+');
			// Serve
			$api->get( '/{file}', [ 'as' => 'serve', 'uses' => 'Cookbook\Api\Http\Controllers\FileServeController@index' ] );

		});

		// Locales
		$api->group(['prefix' => 'locales', 'as' => 'locale::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@show' ] );

		});

		// Workflows
		$api->group(['prefix' => 'workflows', 'as' => 'workflow::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@show' ] );

		});

		// Workflow points
		$api->group(['prefix' => 'workflow-points', 'as' => 'workflow-point::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@show' ] );

		});

		// Users
		$api->group(['prefix' => 'users', 'as' => 'user::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@show' ] );
			// Change password
			$api->post( '/{id}/change-password', [ 'as' => 'changePassword', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@password' ] );

		});

		// Clients
		$api->group(['prefix' => 'clients', 'as' => 'client::'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'uses' => 'Cookbook\Api\Http\Controllers\ClientController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'uses' => 'Cookbook\Api\Http\Controllers\ClientController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'uses' => 'Cookbook\Api\Http\Controllers\ClientController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'uses' => 'Cookbook\Api\Http\Controllers\ClientController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Cookbook\Api\Http\Controllers\ClientController@show' ] );

		});

		// Entities (with type prefix)
		$api->group(['prefix' => '{type}', 'middleware' => 'cb.gettype'], function($api){

			// Create
			$api->post( '/', [ 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@destroy' ] );
			// Get
			$api->get( '/', [ 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@show' ] );

		});
	});
		
	
});

// Route::get(
// 	'test/files/{url}', 
// 	[ 'as' => 'CB::file::serve', 'uses' => 'Cookbook\Api\Http\Controllers\FileServeController@index' ]
// )->where('url', '(.*)');
