<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use munkireport\lib\Modules as ModuleMgr;

class ModuleGraphQLSchemaProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        app('events')->listen(
            \Nuwave\Lighthouse\Events\BuildSchemaString::class,
            function(): string {
                $schema = "";
                $moduleMgr = new ModuleMgr;
                $moduleMgr->loadinfo(true);
                foreach($moduleMgr->getInfo() as $moduleName => $info) {
                    $moduleGqlPath = $moduleMgr->getPath($moduleName, 'schema.graphql');
                    if (file_exists($moduleGqlPath)) {
                        $schema .= file_get_contents($moduleGqlPath);
                    }
                }

                return $schema;
            }
        );
    }
}
