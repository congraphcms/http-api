<?php

Route::resource( 'attributes', 'Cookbook\Api\Http\Controllers\AttributeController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'attribute-sets', 'Cookbook\Api\Http\Controllers\AttributeSetController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'entity-types', 'Cookbook\Api\Http\Controllers\EntityTypeController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'entities', 'Cookbook\Api\Http\Controllers\EntityController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'files', 'Cookbook\Api\Http\Controllers\FileController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'routes', 'Cookbook\Api\Http\Controllers\RouteController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'locales', 'Cookbook\Api\Http\Controllers\LocaleController', [ 'except' => ['create', 'edit'] ] );
Route::resource( '/{entityType}', ['middleware' => 'cb.gettype', 'uses' => 'Cookbook\Api\Http\Controllers\LocaleController', [ 'except' => ['create', 'edit'] ]] );