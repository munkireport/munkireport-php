<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * This service provider adds extra methods which are similar to Laravel's ServiceProvider ->load* methods, but for
 * MunkiReport objects.
 *
 * @package App\Providers
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register the given path location as providing scripts for this module.
     *
     * @param  string  $path
     * @return void
     */
    protected function loadScriptsFrom($path)
    {

    }
}
