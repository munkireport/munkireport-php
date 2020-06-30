<?php


namespace MR\Module;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

abstract class ServiceProvider extends LaravelServiceProvider
{
    /**
     * The scripts that should be run on the client as part of the module installation
     *
     * @var array
     */
    public static $installScripts = [];

    /**
     * The scripts that should be run on the client as part of the module uninstallation
     *
     * @var array
     */
    public static $uninstallScripts = [];

    /**
     * The listings that this module publishes, with a link title.
     * Note: This does not affect the published routes, you still need to write and publish a route
     * that matches this.
     *
     * @var string[]
     */
    public static $listings = [];

    /**
     * @var string[]
     */
    public static $reports = [];

}
