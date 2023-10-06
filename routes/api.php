<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/user', function (Request $request) {
//    return $request->user();
//});

/**
 * Route group reserved for pure Laravel API middleware based API's
 */
Route::group(['prefix' => 'v6', 'namespace' => 'Api', 'middleware' => 'auth'], function () {
    Route::get('/system/php', 'SystemInformationController@php');
    Route::get('/system/database', 'SystemInformationController@database');
});

Route::group(['prefix' => 'v6', 'namespace' => 'Api', 'middleware' => 'auth:sanctum'], function () {
    Route::apiResource('modules', 'ModulesController');
    Route::apiResource('business_units', 'BusinessUnitsController');
    Route::apiResource('machine_groups', 'MachineGroupsController');
    Route::apiResource('users', 'UsersController');
    Route::apiResource('users.contact_methods', 'UsersContactMethodsController');

    Route::get('/search/{model}/{query}', 'SearchController@searchModel')->where('query', '.*');
});

Route::group(['prefix' => 'v5', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');
    Route::post('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');
});
