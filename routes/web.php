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
Route::get('/show/listing/{listing}/{action}', 'ShowController@listing');
Route::get('/show/report/{report}/{action}', 'ShowController@report');
Route::get('/datatables/data', 'DatatablesController@data');


Route::get('/settings/theme', 'SettingsController@theme');

Route::get('/locale/get/{lang?}', 'LocaleController@get');

Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');

