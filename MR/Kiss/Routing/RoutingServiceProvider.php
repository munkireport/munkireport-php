<?php
namespace MR\Kiss\Routing;


class RoutingServiceProvider extends \Illuminate\Routing\RoutingServiceProvider
{
    protected function registerRouter()
    {
        $this->app->singleton('router', function ($app) {
            return new Router($app['events'], $app);
        });
    }
}
