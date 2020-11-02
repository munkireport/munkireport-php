<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use munkireport\lib\Dashboard;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Dashboard::class, function ($app) {
            return new Dashboard(config('dashboard'));
        });
    }
}
