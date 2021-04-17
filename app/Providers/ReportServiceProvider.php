<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ReportServiceProvider
 *
 * Registers a list of modules and classes that handle processing of client reports, I.E the data that is submitted
 * when a client tries to contact ReportController for hash data, or to check in.
 *
 * @package App\Providers
 */
class ReportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Reports::class, \App\Reports::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
