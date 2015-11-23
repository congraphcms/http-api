<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	// Attributes
	$api->group(['prefix' => 'attributes'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'attributes.create', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'attributes.update', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'attributes.delete', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'attributes.get', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'attributes.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeController@show' ] );

	});

	// Attribute Sets
	$api->group(['prefix' => 'attribute-sets'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'attribute-sets.create', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'attribute-sets.update', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'attribute-sets.delete', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'attribute-sets.get', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'attribute-sets.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\AttributeSetController@show' ] );

	});

	// Entity Types
	$api->group(['prefix' => 'entity-types'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'entity-types.create', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'entity-types.update', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'entity-types.delete', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'entity-types.get', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'entity-types.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\EntityTypeController@show' ] );

	});

	// Entities
	$api->group(['prefix' => 'entities'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'entities.create', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'entities.update', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'entities.delete', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'entities.get', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'entities.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@show' ] );

	});

	// Files
	$api->group(['prefix' => 'files'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'files.create', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'files.update', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'files.delete', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'files.get', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'files.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\FileController@show' ] );

	});

	// Locales
	$api->group(['prefix' => 'locales'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'locales.create', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'locales.update', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'locales.delete', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'locales.get', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'locales.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController@show' ] );

	});

	// Workflows
	$api->group(['prefix' => 'workflows'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'workflows.create', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'workflows.update', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'workflows.delete', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'workflows.get', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'workflows.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowController@show' ] );

	});

	// Workflow points
	$api->group(['prefix' => 'workflow-points'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'workflow-points.create', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'workflow-points.update', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'workflow-points.delete', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'workflow-points.get', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'workflow-points.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\WorkflowPointController@show' ] );

	});

	// Users
	$api->group(['prefix' => 'users'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'users.create', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'users.update', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'users.delete', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'users.get', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'users.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\UserController@show' ] );

	});

	// Entities (with type prefix)
	$api->group(['prefix' => '{type}', 'middleware' => 'cb.gettype'], function($api){

		// Create
		$api->post( '/', [ 'as' => 'entities.create', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@store' ] );
		// Update
		$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'entities.update', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@update' ] );
		// Delete
		$api->delete( '/{id}', [ 'as' => 'entities.delete', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@destroy' ] );
		// Get
		$api->get( '/', [ 'as' => 'entities.get', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@index' ] );
		// Fetch
		$api->get( '/{id}', [ 'as' => 'entities.fetch', 'uses' => 'Cookbook\Api\Http\Controllers\EntityController@show' ] );

	});
	
});


