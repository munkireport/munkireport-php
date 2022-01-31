<?php

namespace App\Providers;

use App\Packages;
use Composer\Composer;
use Composer\Factory;
use Composer\IO\BufferIO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
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
        $config = realpath(__DIR__ . '/../../composer.json');
        Log::alert($config);

        $this->app->singleton(Packages::class, function ($app) {
            return new Packages;
        });
    }
}
