<?php

namespace MR\Kiss\Providers;

use Illuminate\Support\ServiceProvider;
use MR\Kiss\Engine;
use MR\Kiss\FakeEngine;

/**
 * This provider only serves to register 'engine' KISS_Engine into the $GLOBALS so that when a view accesses this property
 * you don't generate an exception.
 *
 * @package App\Providers
 */
class KissEngineProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $conf = $GLOBALS['conf'];
        $this->app['engine'] = new FakeEngine();
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
