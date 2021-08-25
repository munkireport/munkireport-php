<?php

namespace App\Providers;

use URL, Config, Str;
use App\Processors;
use Illuminate\Support\ServiceProvider;
use munkireport\processors\MachineProcessor;
use munkireport\processors\ReportDataProcessor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Illuminate\Database\Migrations\Migrator::class, function ($app) {
            return $app['migrator'];
        });

        $this->app->singleton(Processors::class, function ($app) {
            return new Processors();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceRootUrl(Config::get('app.url'));    
        if (Str::contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        $this->callAfterResolving(Processors::Class, function (Processors $processors) {
            $processors->process('reportdata', ReportDataProcessor::class);
            $processors->process('machine', MachineProcessor::class);
        });
    }
}
