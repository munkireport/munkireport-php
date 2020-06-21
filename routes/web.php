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

Route::get('/admin/get_bu_data', 'AdminController@get_bu_data');
Route::get('/admin/get_mg_data', 'AdminController@get_mg_data');
Route::post('/admin/save_business_unit', 'AdminController@save_business_unit');
Route::post('/admin/save_machine_group', 'AdminController@save_machine_group');
Route::get('/admin/show/{which}', 'AdminController@show');

Route::get('/clients/detail/{sn?}', 'ClientsController@detail');
Route::get('/clients/get_data/{serial_number?}', 'ClientsController@get_data');
Route::get('/clients/get_links', 'ClientsController@get_links');

Route::post('/datatables/data', 'DatatablesController@data');

Route::get('/install', 'InstallController@index');
Route::get('/install/get_paths', 'InstallController@get_paths');

Route::get('/locale/get/{lang?}', 'LocaleController@get');
Route::get('/locale/get/{lang}/{load}', 'LocaleController@get');

Route::get('/module_marketplace/get_module_data', 'ModuleMarketplaceController@get_module_data');

Route::post('/report/hash_check', 'ReportController@hash_check');
Route::post('/report/check_in', 'ReportController@check_in');

Route::any('/settings/theme', 'SettingsController@theme');

Route::get('/show/dashboard/{dashboard?}', 'ShowController@dashboard');
Route::get('/show/listing/{module}/{name?}', 'ShowController@listing');
Route::get('/show/report/{report}/{action}', 'ShowController@report');

Route::get('/system/show/{which?}', 'SystemController@show');
Route::get('/system/DataBaseInfo', 'SystemController@DataBaseInfo');
Route::get('/system/phpInfo', 'SystemController@phpInfo');







Route::get('/filter/get_filter', 'FilterController@get_filter');
Route::get('/unit/get_machine_groups', 'UnitController@get_machine_groups');

Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
