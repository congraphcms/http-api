<?php

use Illuminate\Http\Request;

// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: authorization, accept, content-type, x-xsrf-token, x-csrf-token, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
	$api->group(['as' => 'CB' , /*'middleware' => 'cb.cors'*/], function($api){

		// Attributes
		$api->group(['prefix' => 'attributes', 'as' => 'attribute'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\AttributeController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\AttributeController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\AttributeController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\AttributeController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\AttributeController@show' ] );

		});

		// Attribute Sets
		$api->group(['prefix' => 'attribute-sets', 'as' => 'attribute-set'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\AttributeSetController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\AttributeSetController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\AttributeSetController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\AttributeSetController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\AttributeSetController@show' ] );

		});

		// Entity Types
		$api->group(['prefix' => 'entity-types', 'as' => 'entity-type'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\EntityTypeController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\EntityTypeController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\EntityTypeController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\EntityTypeController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\EntityTypeController@show' ] );

		});

		// Entities
		$api->group(['prefix' => 'entities', 'as' => 'entity'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\EntityController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\EntityController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\EntityController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\EntityController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\EntityController@show' ] );

		});

		// Files
		$api->group(['prefix' => 'files', 'as' => 'file'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\FileController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\FileController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\FileController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\FileController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\FileController@show' ] )->where('id', '[0-9]+');
			// Serve
			$api->get( '/{file}', [ 'as' => 'serve', 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\FileServeController@index' ] );

		});

		// Locales
		$api->group(['prefix' => 'locales', 'as' => 'locale'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@show' ] );

		});

		// Workflows
		$api->group(['prefix' => 'workflows', 'as' => 'workflow'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\WorkflowController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\WorkflowController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\WorkflowController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\WorkflowController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\WorkflowController@show' ] );

		});

		// Workflow points
		$api->group(['prefix' => 'workflow-points', 'as' => 'workflow-point'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\WorkflowPointController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\WorkflowPointController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\WorkflowPointController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\WorkflowPointController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_content_model|read_content_model|manage_content'], 'uses' => 'Congraph\Api\Http\Controllers\WorkflowPointController@show' ] );

		});

		// Users
		$api->group(['prefix' => 'users', 'as' => 'user'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_users', 'uses' => 'Congraph\Api\Http\Controllers\UserController@store' ] );
			// Update self
			$api->match(['PUT', 'PATCH'], '/me', [ 'as' => 'updateSelf', 'uses' => 'Congraph\Api\Http\Controllers\UserController@updateSelf' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_users', 'uses' => 'Congraph\Api\Http\Controllers\UserController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_users', 'uses' => 'Congraph\Api\Http\Controllers\UserController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_users|read_users'], 'uses' => 'Congraph\Api\Http\Controllers\UserController@index' ] );
			// Fetch self
			$api->get( '/me', [ 'as' => 'fetch', 'uses' => 'Congraph\Api\Http\Controllers\UserController@showSelf' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_users|read_users'], 'uses' => 'Congraph\Api\Http\Controllers\UserController@show' ] );
			// Change own password
			$api->post( '/me/change-password', [ 'as' => 'changeOwnPassword', 'uses' => 'Congraph\Api\Http\Controllers\UserController@ownPassword' ] );
			// Change password
			$api->post( '/{id}/change-password', [ 'as' => 'changePassword', 'middleware' => 'oauth:manage_users', 'uses' => 'Congraph\Api\Http\Controllers\UserController@password' ] );

		});

		// Roles
		$api->group(['prefix' => 'roles', 'as' => 'role'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_roles', 'uses' => 'Congraph\Api\Http\Controllers\RoleController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_roles', 'uses' => 'Congraph\Api\Http\Controllers\RoleController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_roles', 'uses' => 'Congraph\Api\Http\Controllers\RoleController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_roles|read_roles|manage_users|manage_clients'], 'uses' => 'Congraph\Api\Http\Controllers\RoleController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_roles|read_roles|manage_users|manage_clients'], 'uses' => 'Congraph\Api\Http\Controllers\RoleController@show' ] );

		});

		// Clients
		$api->group(['prefix' => 'clients', 'as' => 'client'], function($api){

			// Create
			$api->post( '/', [ 'as' => 'create', 'middleware' => 'oauth:manage_clients', 'uses' => 'Congraph\Api\Http\Controllers\ClientController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'as' => 'update', 'middleware' => 'oauth:manage_clients', 'uses' => 'Congraph\Api\Http\Controllers\ClientController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'as' => 'delete', 'middleware' => 'oauth:manage_clients', 'uses' => 'Congraph\Api\Http\Controllers\ClientController@destroy' ] );
			// Get
			$api->get( '/', [ 'as' => 'get', 'middleware' => ['oauth:manage_clients|read_clients'], 'uses' => 'Congraph\Api\Http\Controllers\ClientController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'as' => 'fetch', 'middleware' => ['oauth:manage_clients|read_clients'], 'uses' => 'Congraph\Api\Http\Controllers\ClientController@show' ] );

		});


		// DELIVERY API //
		//////////////////
		$api->group(['prefix' => 'delivery', 'as' => 'delivery'], function($api){
				// Entities
			$api->group(['prefix' => 'entities', 'as' => 'entity'], function($api){
				// Get
				$api->get( '/', [ 'as' => 'get', 'uses' => 'Congraph\Api\Http\Controllers\EntityDeliveryController@index' ] );
				// Fetch
				$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Congraph\Api\Http\Controllers\EntityDeliveryController@show' ] );

			});

			// Files
			$api->group(['prefix' => 'files', 'as' => 'file'], function($api){
				// Get
				$api->get( '/', [ 'as' => 'get', 'uses' => 'Congraph\Api\Http\Controllers\FileController@index' ] );
				// Fetch
				$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Congraph\Api\Http\Controllers\FileController@show' ] )->where('id', '[0-9]+');
				// Serve
				$api->get( '/{file}', [ 'as' => 'serve', 'uses' => 'Congraph\Api\Http\Controllers\FileServeController@index' ] );

			});

			// Locales
			$api->group(['prefix' => 'locales', 'as' => 'locale'], function($api){
				// Get
				$api->get( '/', [ 'as' => 'get', 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@index' ] );
				// Fetch
				$api->get( '/{id}', [ 'as' => 'fetch', 'uses' => 'Congraph\Api\Http\Controllers\LocaleController@show' ] );

			});

			// Entities (with type prefix)
			$api->group(['prefix' => '{type}', 'middleware' => 'cb.gettype'], function($api){
				// Get
				$api->get( '/', [ 'uses' => 'Congraph\Api\Http\Controllers\EntityDeliveryController@index' ] );
				// Fetch
				$api->get( '/{id}', [ 'uses' => 'Congraph\Api\Http\Controllers\EntityDeliveryController@show' ] );

			});
		});

		// Entities (with type prefix)
		$api->group(['prefix' => '{type}', 'middleware' => 'cb.gettype'], function($api){

			// Create
			$api->post( '/', [ 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\EntityController@store' ] );
			// Update
			$api->match(['PUT', 'PATCH'], '/{id}', [ 'middleware' => 'oauth:manage_content_model', 'uses' => 'Congraph\Api\Http\Controllers\EntityController@update' ] );
			// Delete
			$api->delete( '/{id}', [ 'middleware' => 'oauth:manage_content', 'uses' => 'Congraph\Api\Http\Controllers\EntityController@destroy' ] );
			// Get
			$api->get( '/', [ 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\EntityController@index' ] );
			// Fetch
			$api->get( '/{id}', [ 'middleware' => ['oauth:manage_content|read_content'], 'uses' => 'Congraph\Api\Http\Controllers\EntityController@show' ] );

		});

		
	});


});

Route::post(
	'oauth/access_token', 
	[
		// 'middleware' => 'cb.cors', 
		'uses' => 'Congraph\Api\Http\Controllers\OAuthController@issue'
	]
);
Route::post(
	'oauth/revoke_token', 
	[
		// 'middleware' => 'cb.cors', 
		'uses' => 'Congraph\Api\Http\Controllers\OAuthController@revoke'
	]
);
Route::get(
	'oauth/owner', 
	[
		// 'middleware' => 'cb.cors', 
		'uses' => 'Congraph\Api\Http\Controllers\OAuthController@owner'
	]
);

// Route::get(
// 	'test/files/{url}',
// 	[ 'as' => 'CB::file::serve', 'uses' => 'Congraph\Api\Http\Controllers\FileServeController@index' ]
// )->where('url', '(.*)');
