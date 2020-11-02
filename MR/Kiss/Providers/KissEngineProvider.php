<?php

namespace MR\Kiss\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Load config
        initConfig();
        configAppendFile(APP_ROOT . 'app/config/db.php', 'connection');
        // echo '<pre>';print_r($GLOBALS['conf']);exit;

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
