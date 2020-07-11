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

Route::get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Route group reserved for pure Laravel API middleware based API's
 */
Route::group(['prefix' => 'v6', 'namespace' => 'Api'], function () {
    Route::get('/phpinfo', 'SystemInformationController@phpinfo');
    Route::get('/database/health', 'SystemInformationController@databaseHealth');
});
