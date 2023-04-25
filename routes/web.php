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

Route::get('/oauth2/redirect/{provider}',
    [\App\Http\Controllers\Auth\Oauth2Controller::class, 'redirect'])->name('oauth2_redirect');
Route::get('/oauth2/callback/{provider}',
    [\App\Http\Controllers\Auth\Oauth2Controller::class, 'callback'])->name('oauth2_callback');

Route::redirect('/', '/show/dashboard/default');

Route::middleware(['auth'])->group(function () {
    Route::get('/clients', 'ClientsController@index');
    Route::get('/clients/install', 'ClientsController@install');
    Route::get('/clients/detail/{sn?}', 'ClientsController@detail');
    Route::get('/clients/get_data/{serial_number?}', 'ClientsController@get_data');
    Route::get('/clients/get_links', 'ClientsController@get_links');
    Route::get('/clients/show/{which?}', 'ClientsController@show');

    Route::post('/datatables/data', 'DatatablesController@data');

    Route::get('/filter/get_filter', 'FilterController@get_filter');
    Route::post('/filter/set_filter', 'FilterController@set_filter');
    Route::get('/unit/get_machine_groups', 'UnitController@get_machine_groups');

    Route::get('/locale/get/{lang?}', 'LocaleController@get'); // For older (mr 5.x i18next)
    Route::get('/locale/get/{lang}/{load}', 'LocaleController@get'); // For older (mr 5.x i18next)
    Route::get('/locales/{lng}/{ns}.json', 'LocalesController@get'); // For newest i18Next
    Route::get('/locales', 'LocalesController@index');

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
    // Ditto tag
    Route::get('/module/tag/listing', 'TagController@listing');
    Route::post('/module/tag/save', 'TagController@save');
    Route::get('/module/tag/retrieve/{serial_number}', 'TagController@retrieve');
    Route::get('/module/tag/delete/{serial_number}/{id?}', 'TagController@delete');
    Route::get('/module/tag/all_tags/{add_count?}', 'TagController@all_tags');
    // Ditto comment
    Route::post('/module/comment/save', 'CommentController@save');
    Route::get('/module/comment/retrieve/{serial_number}/{section?}', 'CommentController@retrieve');
    // Event
    Route::get('/module/event/get/{limit?}', 'EventController@get');


    Route::any('/settings/theme', 'SettingsController@theme');

    Route::get('/show', 'ShowController@index');
    Route::get('/show/custom/{which?}', 'ShowController@custom');
    Route::get('/show/dashboard/default', 'DashboardsController@default'); // Temporary override for testing Widget Components
    Route::get('/show/dashboard/{dashboard?}', 'ShowController@dashboard');
    Route::get('/show/listing/{module}/{name?}', 'ShowController@listing');
    Route::get('/show/report/{report}/{action}', 'ShowController@report');
    Route::get('/show/kiss_layout', 'ShowController@kiss_layout'); // For comparison of head.php/foot.php against blade layouts eg. mr.blade.php

    Route::get('/me', 'MeController@index');
    Route::get('/me/tokens', 'MeController@tokens');
    Route::get('/me/profile', 'MeController@profile');

    Route::delete('/manager/delete_machine/{serial_number}', 'ManagerController@delete_machine');

    if (config('_munkireport.alpha_features.dashboards', false)) {
        Route::get('/dashboards/{slug?}', 'DashboardsController@index');
    }

    //Route::get('/search/{model}/{query}', 'Api\SearchController@searchModel')->where('query', '.*');

    // Laravel JetStream-Alike Grafting
//    Route::get('/user/profile', [\App\Http\Controllers\UserProfileController::class, 'show'])
//        ->name('profile.show');
});

// NOTE: These cannot be completely behind auth because the get_script() action needs to be accessible without authentication.
Route::get('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');
Route::post('/module/{module}/{action}/{params?}', 'ModuleController@invoke')->where('params', '.*');

Route::middleware(['auth'])->group(function () {
    Route::post('/archiver/update_status/{serial_number}', 'ArchiverController@update_status');
    Route::post('/archiver/bulk_update_status', 'ArchiverController@bulk_update_status');
    Route::get('/manager/delete_machine/{serial_number?}', 'ManagerController@delete_machine');
});
