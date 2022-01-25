<?php

namespace Compatibility\Kiss\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Register the KISSMVC \View class as a first-class View provider for Illuminate\View\Engines\EngineResolver for the
 * .php extension (instead of Illuminate\View\Engines\PhpEngine)
 *
 * @package App\Providers
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerKissMvcViewEngine();
    }

    private function registerKissMvcViewEngine()
    {
        $this->app['view.engine.resolver']->register('php', function () {
            return new \Compatibility\Kiss\View\Engines\KissEngine;
        });
    }
}
