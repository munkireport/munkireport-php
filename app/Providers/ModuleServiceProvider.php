<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Database\Migrations\Migrator;
use munkireport\lib\Modules;

/**
 * This provider extends Laravel to deal with dynamically loading items out of munkireport modules without creating
 * providers for those modules.
 *
 * @package App\Providers
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Modules::class, function ($app) {
            $modules = new Modules;
            $modules->loadInfo(false);
            return $modules;
        });

        $moduleInfo = app(Modules::class)->getInfo();

        // Decorate Eloquent Factory with module paths
        $this->app->extend(EloquentFactory::class, function (EloquentFactory $service, $app) use ($moduleInfo) {


            foreach($moduleInfo as $moduleName => $info) {
                $factorypath = app(Modules::class)->getPath($moduleName, "/${moduleName}_factory.php");

                if (is_file($factorypath)) {
                    $this->loadFactoryFrom($service, $factorypath);
                }
            }

            return $service;
        });

        // Decorate Migrator with Module Paths
        $this->app->extend(Migrator::class, function (Migrator $service, $app) use ($moduleInfo) {
            foreach ($moduleInfo as $moduleName => $info) {
                if (app(Modules::class)->getModuleMigrationPath($moduleName, $migrationPath)) {
                    $service->path($migrationPath);
                }
            }

            return $service;
        });
    }

    /**
     * Load a factory class from a specific absolute path.
     *
     * @param EloquentFactory $service
     * @param string $filepath
     */
    protected function loadFactoryFrom(EloquentFactory &$service, string $filepath)
    {
        $factory = $service; // The factory php script will expect this in scope
        require $filepath;
    }


}
