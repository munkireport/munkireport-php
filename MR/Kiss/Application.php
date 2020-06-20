<?php
namespace MR\Kiss;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Log\LogServiceProvider;
use MR\Kiss\Routing\RoutingServiceProvider;

class Application extends \Illuminate\Foundation\Application
{
    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
        $this->register(new LogServiceProvider($this));
        $this->register(new RoutingServiceProvider($this));
    }
}
