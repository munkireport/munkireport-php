<?php

$driver = env('CONNECTION_DRIVER', 'sqlite');
switch ($driver) {
  case 'sqlite':
    return [
        'driver'    => 'sqlite',
        'database'  => env('CONNECTION_DATABASE', APP_ROOT . 'app/db/db.sqlite'),
        'username' => '',
        'password' => '',
        'options' => env('CONNECTION_OPTIONS', []),
    ];
    break;
  case 'mysql':
    return [
      'driver'    => 'mysql',
      'host' => env('CONNECTION_HOST', '127.0.0.1'),
      'port' => env('CONNECTION_PORT', 3306),
      'database' => env('CONNECTION_DATABASE', 'munkireport'),
      'username' => env('CONNECTION_USERNAME', 'munkireport'),
      'password' => env('CONNECTION_PASSWORD', 'munkireport'),
      'charset' => env('CONNECTION_CHARSET', 'utf8mb4'),
      'collation' => env('CONNECTION_COLLATION', 'utf8mb4_unicode_ci'),
      'strict' => env('CONNECTION_STRICT', true),
      'engine' => env('CONNECTION_ENGINE', 'InnoDB'),
      'ssl_enabled' => env('CONNECTION_SSL_ENABLED', false),
      'ssl_key' => env('CONNECTION_SSL_KEY'),
      'ssl_cert' => env('CONNECTION_SSL_CERT'),
      'ssl_ca' => env('CONNECTION_SSL_CA'),
      'ssl_capath' => env('CONNECTION_SSL_CAPATH'),
      'ssl_cipher' => env('CONNECTION_SSL_CIPHER'),
      'options' => env('CONNECTION_OPTIONS', []),
    ];
  default:
      throw new \Exception(sprintf("Unknown driver: %s", $driver), 1);
      break;
}
