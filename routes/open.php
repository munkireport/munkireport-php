<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Open Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes that are not normally authenticated
| or have their own unique authentication
|
*/

Route::get('/install', 'InstallController@index');
Route::get('/install/dump_modules/{format}', 'InstallController@dump_modules');
Route::get('/install/get_paths', 'InstallController@get_paths');
Route::get('/install/modules', 'InstallController@modules');

Route::post('/report/broken_client', 'ReportController@broken_client');
Route::post('/report/check_in', 'ReportController@check_in');
Route::post('/report/hash_check', 'ReportController@hash_check');

