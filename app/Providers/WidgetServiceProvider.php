<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use munkireport\lib\Widgets;

class WidgetServiceProvider extends ServiceProvider
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
        $this->app->singleton(Widgets::class, function ($app) {
            return new Widgets(config('widget'));
        });
    }
}
