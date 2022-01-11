<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| These routes represent reporting API available to the munkireport runner
| to submit new data.
|
*/
Route::post('/report/broken_client', 'ReportController@broken_client');
Route::post('/report/check_in', 'ReportController@check_in');
Route::post('/report/hash_check', 'ReportController@hash_check');
