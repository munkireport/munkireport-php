<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| These routes are protected by the `global` guard, which means global admin
| in the context of MunkiReport. Only admins will be able to access these
| routes.
|
*/

Route::middleware(['auth', 'can:global'])->group(function () {
    Route::get('/admin/get_bu_data', 'AdminController@get_bu_data');
    Route::get('/admin/get_mg_data', 'AdminController@get_mg_data');
    Route::post('/admin/save_business_unit', 'AdminController@save_business_unit');
    Route::post('/admin/remove_business_unit', 'AdminController@remove_business_unit');
    Route::post('/admin/save_machine_group', 'AdminController@save_machine_group');
    Route::get('/admin/remove_machine_group/{groupid?}', 'AdminController@remove_machine_group');
    Route::get('/admin/show/{which}', 'AdminController@show');

    // TODO: Deprecated /system/show for /system, add redirect
    //Route::get('/system/show/{which?}', 'SystemController@show');
//    Route::get('/system/DataBaseInfo', 'SystemController@DataBaseInfo');
//    Route::get('/system/phpInfo', 'SystemController@phpInfo'); // Ajax/XHR deprecated for actual phpinfo page because zero parsing errors or maintenance.
    Route::get('/system/php_info', 'SystemController@php_info');
    Route::get('/system/status', 'SystemController@status');
    Route::get('/system/database', 'SystemController@database');
    Route::get('/system/widgets', 'SystemController@widgets');

    Route::get('/database/migrate', 'DatabaseController@migrate');

    // BU v2 Alpha
    if (config('_munkireport.alpha_features.business_units_v2', false)) {
        Route::get('/business-units/{id?}', 'BusinessUnitsController@index');
    }

    Route::get('/unit/get_data', 'UnitController@get_data');

    Route::get('/module_marketplace', 'ModuleMarketplaceController@index');
    Route::get('/module_marketplace/get_module_data', 'ModuleMarketplaceController@get_module_data');
    Route::get('/module_marketplace/get_module_info', 'ModuleMarketplaceController@get_module_info');

    Route::get('/admin/users', 'UsersController@index');
});
