<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

// Users cannot self-register
Auth::routes(['register' => false]);

Route::redirect('/', '/show/dashboard/default');

Route::middleware(['auth'])->group(function () {
    Route::get('/clients', 'ClientsController@index');
    Route::get('/clients/detail/{sn?}', 'ClientsController@detail');
    Route::get('/clients/get_data/{serial_number?}', 'ClientsController@get_data');
    Route::get('/clients/get_links', 'ClientsController@get_links');
    Route::get('/clients/show/{which?}', 'ClientsController@show');

    Route::post('/datatables/data', 'DatatablesController@data');

    Route::get('/filter/get_filter', 'FilterController@get_filter');
    Route::post('/filter/set_filter', 'FilterController@set_filter');
    Route::get('/unit/get_machine_groups', 'UnitController@get_machine_groups');

    Route::get('/locale/get/{lang?}', 'LocaleController@get');
    Route::get('/locale/get/{lang}/{load}', 'LocaleController@get');


    // Redirect old ReportData module routes back to the Core Controller
    Route::get('/module/reportdata/report/{serial_number}', 'ReportDataController@report');
    Route::get('/module/reportdata/get_groups', 'ReportDataController@get_groups');
    Route::get('/module/reportdata/get_inactive_days', 'ReportDataController@get_inactive_days');
    Route::get('/module/reportdata/get_lastseen_stats', 'ReportDataController@get_lastseen_stats');
    Route::get('/module/reportdata/getUptimeStats', 'ReportDataController@getUptimeStats');
    Route::get('/module/reportdata/new_clients', 'ReportDataController@new_clients');
    Route::get('/module/reportdata/new_clients2', 'ReportDataController@new_clients2');
    Route::get('/module/reportdata/ip', 'ReportDataController@ip');
    // Ditto for Machine
    Route::get('/module/machine/get_duplicate_computernames', 'MachineController@get_duplicate_computernames');
    Route::get('/module/machine/get_model_stats/{summary?}', 'MachineController@get_model_stats');
    Route::get('/module/machine/report/{serial_number?}', 'MachineController@report');
    Route::get('/module/machine/new_clients', 'MachineController@new_clients');
    Route::get('/module/machine/get_memory_stats/{format?}', 'MachineController@get_memory_stats');
    Route::get('/module/machine/hw', 'MachineController@hw');
    Route::get('/module/machine/os', 'MachineController@os');
    Route::get('/module/machine/osbuild', 'MachineController@osbuild');
    Route::get('/module/machine/model_lookup/{serial_number}', 'MachineController@model_lookup');

    Route::any('/settings/theme', 'SettingsController@theme');

    Route::get('/show', 'ShowController@index');
    Route::get('/show/custom/{which?}', 'ShowController@custom');
    Route::get('/show/dashboard/{dashboard?}', 'ShowController@dashboard');
    Route::get('/show/listing/{module}/{name?}', 'ShowController@listing');
    Route::get('/show/report/{report}/{action}', 'ShowController@report');
    Route::get('/show/kiss_layout', 'ShowController@kiss_layout');

    Route::get('/me', 'MeController@index');
    Route::get('/me/tokens', 'MeController@tokens');


    if (config('_munkireport.alpha_features.dashboards', false)) {
        Route::get('/dashboards', 'DashboardsController@index');
    }
});

// NOTE: These cannot be completely behind auth because the get_script() action needs to be accessible without authentication.
Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');
Route::post('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');

Route::middleware(['auth'])->group(function () {
    Route::post('/archiver/update_status/{serial_number}', 'ArchiverController@update_status');
    Route::post('/archiver/bulk_update_status', 'ArchiverController@bulk_update_status');
});

Route::middleware(['auth', 'can:delete_machine'])->group(function () {
    Route::get('/manager/delete_machine/{serial_number?}', 'ManagerController@post');
});


