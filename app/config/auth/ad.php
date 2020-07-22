<?php

$ssl_options = [];
if (env('AUTH_AD_TLS_CACERTDIR'))   $ssl_options[LDAP_OPT_X_TLS_CACERTDIR]     = env('AUTH_AD_TLS_CACERTDIR');
if (env('AUTH_AD_TLS_CACERTFILE'))   $ssl_options[LDAP_OPT_X_TLS_CACERTFILE]   = env('AUTH_AD_TLS_CACERTFILE');
if (env('AUTH_AD_TLS_CERTFILE'))     $ssl_options[LDAP_OPT_X_TLS_CERTFILE]     = env('AUTH_AD_TLS_CERTFILE');
if (env('AUTH_AD_TLS_KEYFILE'))      $ssl_options[LDAP_OPT_X_TLS_KEYFILE]      = env('AUTH_AD_TLS_KEYFILE');
if (env('AUTH_AD_TLS_CIPHER_SUITE')) $ssl_options[LDAP_OPT_X_TLS_CIPHER_SUITE] = env('AUTH_AD_TLS_CIPHER_SUITE');
if (env('AUTH_AD_TLS_REQUIRE_CERT')) $ssl_options[LDAP_OPT_X_TLS_REQUIRE_CERT] = env('AUTH_AD_TLS_REQUIRE_CERT');

return [
  'schema'                   => env('AUTH_AD_SCHEMA', 'ActiveDirectory'),
  'account_prefix'           => env('AUTH_AD_ACCOUNT_PREFIX', NULL),
  'account_suffix'           => env('AUTH_AD_ACCOUNT_SUFFIX', NULL),
  'username'                 => env('AUTH_AD_USERNAME', NULL),
  'password'                 => env('AUTH_AD_PASSWORD', NULL),
  'base_dn'                  => env('AUTH_AD_BASE_DN', 'dc=mydomain,dc=local'),
  'hosts'                    => env('AUTH_AD_HOSTS', []),
  'port'                     => env('AUTH_AD_PORT', 389),
  'follow_referrals'         => env('AUTH_AD_FOLLOW_REFERRALS', false),
  'use_ssl'                  => env('AUTH_AD_USE_SSL', false),
  'use_tls'                  => env('AUTH_AD_USE_TLS', false),
  'version'                  => env('AUTH_AD_VERSION', 3),
  'timeout'                  => env('AUTH_AD_TIMEOUT', 5),
  'custom_options'           => $ssl_options,
  'mr_allowed_users'         => env('AUTH_AD_ALLOWED_USERS', []),
  'mr_allowed_groups'        => env('AUTH_AD_ALLOWED_GROUPS', []),
  'mr_recursive_groupsearch' => env('AUTH_AD_RECURSIVE_GROUPSEARCH', false),
];
