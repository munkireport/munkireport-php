<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Faker\Generator as FakerGenerator;

class FakerServiceProvider extends ServiceProvider
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
        $this->app->extend(FakerGenerator::class, function (FakerGenerator $service, $app) {
            $service->addProvider(new \App\Faker\MacProvider());

            return $service;
        });
    }
}
