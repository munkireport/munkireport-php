<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::redirect('/', '/show/dashboard/default');

Route::get('/admin/get_bu_data', 'AdminController@get_bu_data');
Route::get('/admin/get_mg_data', 'AdminController@get_mg_data');
Route::post('/admin/save_business_unit', 'AdminController@save_business_unit');
Route::post('/admin/remove_business_unit', 'AdminController@remove_business_unit');
Route::post('/admin/save_machine_group', 'AdminController@save_machine_group');
Route::post('/admin/remove_machine_group', 'AdminController@remove_machine_group');
Route::get('/admin/show/{which}', 'AdminController@show');

Route::post('/archiver/update_status/{serial_number?}', 'ArchiverController@update_status');
Route::post('/archiver/bulk_update_status', 'ArchiverController@bulk_update_status');

Route::get('/clients', 'ClientsController@index');
Route::get('/clients/detail/{sn?}', 'ClientsController@detail');
Route::get('/clients/get_data/{serial_number?}', 'ClientsController@get_data');
Route::get('/clients/get_links', 'ClientsController@get_links');
Route::get('/clients/show/{which?}', 'ClientsController@show');

Route::post('/datatables/data', 'DatatablesController@data');

Route::get('/filter/get_filter', 'FilterController@get_filter');
Route::post('/filter/set_filter', 'FilterController@set_filter');

Route::get('/install', 'InstallController@index');
Route::get('/install/dump_modules/{format}', 'InstallController@dump_modules');
Route::get('/install/get_paths', 'InstallController@get_paths');
Route::get('/install/modules', 'InstallController@modules');
Route::get('/install/plist', 'InstallController@plist');

Route::get('/locale/get/{lang?}', 'LocaleController@get');
Route::get('/locale/get/{lang}/{load}', 'LocaleController@get');

Route::get('/manager/delete_machine/{serial_number?}', 'ManagerController@post');

Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');

Route::get('/module_marketplace/get_module_data', 'ModuleMarketplaceController@get_module_data');
Route::get('/module_marketplace/get_module_info', 'ModuleMarketplaceController@get_module_info');

Route::post('/report/broken_client', 'ReportController@broken_client');
Route::post('/report/check_in', 'ReportController@check_in');
Route::post('/report/hash_check', 'ReportController@hash_check');

Route::any('/settings/theme', 'SettingsController@theme');

Route::get('/show', 'ShowController@index');
Route::get('/show/custom/{which?}', 'ShowController@custom');
Route::get('/show/dashboard/{dashboard?}', 'ShowController@dashboard');
Route::get('/show/listing/{module}/{name?}', 'ShowController@listing');
Route::get('/show/report/{report}/{action}', 'ShowController@report');

Route::get('/system/show/{which?}', 'SystemController@show');
Route::get('/system/DataBaseInfo', 'SystemController@DataBaseInfo');
Route::get('/system/phpInfo', 'SystemController@phpInfo');

Route::get('/unit/get_data', 'UnitController@get_data');
Route::get('/unit/get_machine_groups', 'UnitController@get_machine_groups');
Route::get('/unit/listing/{which?}', 'UnitController@listing');
Route::get('/unit/reports/{which?}', 'UnitController@listing');
