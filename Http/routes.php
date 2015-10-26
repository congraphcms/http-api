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


