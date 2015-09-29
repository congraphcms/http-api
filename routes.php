<?php

Route::resource( 'attributes', 'Cookbook\Api\Http\Controllers\AttributeController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'attribute-sets', 'Cookbook\Api\Http\Controllers\AttributeSetController', [ 'except' => ['create', 'edit'] ] );
Route::resource( 'entity-types', 'Cookbook\Api\Http\Controllers\EntityTypeController', [ 'except' => ['create', 'edit'] ] );