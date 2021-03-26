<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', env('CONNECTION_DRIVER', 'mysql')),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', env('CONNECTION_DATABASE', app_path('db/db.sqlite'))),
//            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', env('CONNECTION_HOST', '127.0.0.1')),
            'port' => env('DB_PORT', env('CONNECTION_PORT', '3306')),
            'database' => env('DB_DATABASE', env('CONNECTION_DATABASE', 'munkireport')),
            'username' => env('DB_USERNAME', env('CONNECTION_USERNAME', 'munkireport')),
            'password' => env('DB_PASSWORD', env('CONNECTION_PASSWORD', '')),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('CONNECTION_CHARSET', 'utf8mb4'),
            'collation' => env('CONNECTION_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => env('CONNECTION_ENGINE'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                // PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                // From site_helper.php:add_mysql_opts. This was always run if mysql was selected
                // \PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s COLLATE %s', $conn['charset'], $conn['collation']),
                // PDO::ATTR_EMULATE_PREPARES => true,
                PDO::MYSQL_ATTR_SSL_KEY => env('CONNECTION_SSL_KEY'),
                PDO::MYSQL_ATTR_SSL_CERT => env('CONNECTION_SSL_CERT'),
                PDO::MYSQL_ATTR_SSL_CA => env('CONNECTION_SSL_CA'),
                PDO::MYSQL_ATTR_SSL_CAPATH => env('CONNECTION_SSL_CAPATH'),
                PDO::MYSQL_ATTR_SSL_CIPHER => env('CONNECTION_SSL_CIPHER'),
            ]) : [],
        ],

        // Not supported by MunkiReport
//        'pgsql' => [
//            'driver' => 'pgsql',
//            'url' => env('DATABASE_URL'),
//            'host' => env('DB_HOST', env('CONNECTION_HOST', '127.0.0.1')),
//            'port' => env('DB_PORT', env('CONNECTION_PORT', '5432')),
//            'database' => env('DB_DATABASE', env('CONNECTION_DATABASE', 'munkireport')),
//            'username' => env('DB_USERNAME', env('CONNECTION_USERNAME', 'munkireport')),
//            'password' => env('DB_PASSWORD', env('CONNECTION_PASSWORD', '')),
//            'charset' => 'utf8',
//            'prefix' => '',
//            'prefix_indexes' => true,
//            'schema' => 'public',
//            'sslmode' => 'prefer',
//        ],
//
//        'sqlsrv' => [
//            'driver' => 'sqlsrv',
//            'url' => env('DATABASE_URL'),
//            'host' => env('DB_HOST', env('CONNECTION_HOST', 'localhost')),
//            'port' => env('DB_PORT', env('CONNECTION_PORT', '1433')),
//            'database' => env('DB_DATABASE', env('CONNECTION_DATABASE', 'munkireport')),
//            'username' => env('DB_USERNAME', env('CONNECTION_USERNAME', 'munkireport')),
//            'password' => env('DB_PASSWORD', env('CONNECTION_PASSWORD', '')),
//            'charset' => 'utf8',
//            'prefix' => '',
//            'prefix_indexes' => true,
//        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
