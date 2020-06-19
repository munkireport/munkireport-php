<?php

$driver = mr_env('CONNECTION_DRIVER', 'sqlite');
switch ($driver) {
  case 'sqlite':
    return [
        'driver'    => 'sqlite',
        'database'  => mr_env('CONNECTION_DATABASE', APP_ROOT . 'app/db/db.sqlite'),
        'username' => '',
        'password' => '',
        'options' => mr_env('CONNECTION_OPTIONS', []),
    ];
    break;
  case 'mysql':
    return [
      'driver'    => 'mysql',
      'host' => mr_env('CONNECTION_HOST', '127.0.0.1'),
      'port' => mr_env('CONNECTION_PORT', 3306),
      'database' => mr_env('CONNECTION_DATABASE', 'munkireport'),
      'username' => mr_env('CONNECTION_USERNAME', 'munkireport'),
      'password' => mr_env('CONNECTION_PASSWORD', 'munkireport'),
      'charset' => mr_env('CONNECTION_CHARSET', 'utf8mb4'),
      'collation' => mr_env('CONNECTION_COLLATION', 'utf8mb4_unicode_ci'),
      'strict' => mr_env('CONNECTION_STRICT', true),
      'engine' => mr_env('CONNECTION_ENGINE', 'InnoDB'),
      'ssl_enabled' => mr_env('CONNECTION_SSL_ENABLED', false),
      'ssl_key' => mr_env('CONNECTION_SSL_KEY'),
      'ssl_cert' => mr_env('CONNECTION_SSL_CERT'),
      'ssl_ca' => mr_env('CONNECTION_SSL_CA'),
      'ssl_capath' => mr_env('CONNECTION_SSL_CAPATH'),
      'ssl_cipher' => mr_env('CONNECTION_SSL_CIPHER'),
      'options' => mr_env('CONNECTION_OPTIONS', []),
    ];
  default:
      throw new \Exception(sprintf("Unknown driver: %s", $driver), 1);
      break;
}
