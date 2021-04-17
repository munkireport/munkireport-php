<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use munkireport\lib\Modules as ModuleMgr;

class ModuleGraphQLSchemaProvider extends ServiceProvider
{
    /**
     * The GraphQL library, Lighthouse, cannot autoload schema files out of multiple directories unless you
     * hook into certain events. This service provider looks for GraphQL schema definitions in each module directory
     * when Lighthouse attempts to resolve a list of Schemas.
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
                    $moduleGqlPath = $moduleMgr->getPath($moduleName) . 'schema.graphql';
                    if (file_exists($moduleGqlPath)) {
                        $schema .= file_get_contents($moduleGqlPath);
                    }
                }

                return $schema;
            }
        );
    }
}
