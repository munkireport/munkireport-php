<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Route configuration
    |--------------------------------------------------------------------------
    |
    | Set the URI at which the GraphQL Playground can be viewed
    | and any additional configuration for the route.
    |
    */

    'route' => [
        'uri' => '/graphql-playground',
        'name' => 'graphql-playground',
        // 'middleware' => ['web']
        // 'prefix' => '',
        // 'domain' => 'graphql.' . env('APP_DOMAIN', 'localhost'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default GraphQL endpoint
    |--------------------------------------------------------------------------
    |
    | The default endpoint that the Playground UI is set to.
    | It assumes you are running GraphQL on the same domain
    | as GraphQL Playground, but can be set to any URL.
    |
    */

    'endpoint' => '/graphql',

    /*
    |--------------------------------------------------------------------------
    | Control Playground availability
    |--------------------------------------------------------------------------
    |
    | Control if the playground is accessible at all.
    | This allows you to disable it in certain environments,
    | for example you might not want it active in production.
    |
    */

    'enabled' => env('GRAPHQL_PLAYGROUND_ENABLED', true),
];
