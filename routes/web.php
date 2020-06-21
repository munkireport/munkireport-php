<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/show/dashboard/default');

Route::get('/show/dashboard/{dashboard?}', 'ShowController@dashboard');
Route::get('/show/listing/{module}/{name?}', 'ShowController@listing');
Route::get('/show/report/{report}/{action}', 'ShowController@report');
Route::post('/datatables/data', 'DatatablesController@data');
Route::get('/install', 'InstallController@index');
Route::get('/install/get_paths', 'InstallController@get_paths');

Route::post('/report/hash_check', 'ReportController@hash_check');

Route::any('/settings/theme', 'SettingsController@theme');

Route::get('/locale/get/{lang?}', 'LocaleController@get');

Route::get('/filter/get_filter', 'FilterController@get_filter');
Route::get('/unit/get_machine_groups', 'UnitController@get_machine_groups');

Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');

