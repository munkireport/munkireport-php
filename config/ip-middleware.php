<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ignore environments
    |--------------------------------------------------------------------------
    |
    | Environments put in this list will be ignore by middleware.
    |
    */

    'ignore_environments' => [
        'local',
        'testing',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP error code
    |--------------------------------------------------------------------------
    |
    | HTTP error code when request gets rejected.
    | Default is 403 (Forbidden).
    |
    */

    'error_code' => Illuminate\Http\Response::HTTP_FORBIDDEN,

    /*
    |--------------------------------------------------------------------------
    | Custom server parameter
    |--------------------------------------------------------------------------
    |
    | By default, if this configuration value is set to null middleware will use
    | $_SERVER['REMOTE_ADDR'] parameter to get client IP. If you want to get IP
    | address from different $_SERVER parameter you can set it here.
    |
    | For example, if application is behind "CloudFlare" proxy, then 'REMOTE_ADDR'
    | won't return real client IP, instead CloudFlare sets special
    | $_SERVER['HTTP_CF_CONNECTING_IP'] parameter. You need to set following
    | configuration to that parameter name.
    | 'custom_server_parameter' => 'HTTP_CF_CONNECTING_IP',
    |
    */

    'custom_server_parameter' => null,

    /*
    |--------------------------------------------------------------------------
    | Predefined IP lists
    |--------------------------------------------------------------------------
    |
    | Here you can predefine IP list that middleware can use with list key name.
    | You can use array list or comma separated string to set IP addresses.
    | Or you can use environment key.
    |
    */

    'predefined_lists' => [
        'ip-whitelist' => env('AUTH_NETWORK_WHITELIST_IP4'),
//        'list-1' => ['1.1.1.1', '2.2.2.2'],
//        'list-2' => '3.3.3.3,4.4.4.4',
//        'list-3' => env('IP_WHITELIST'), // in .env file: IP_WHITELIST=5.5.5.5,6.6.6.6,7.7.7.7
    ],
];
