<?php

return [
  'schema'                   => env('AUTH_AD_SCHEMA', 'ActiveDirectory'),
  'account_prefix'           => env('AUTH_AD_ACCOUNT_PREFIX', null),
  'account_suffix'           => env('AUTH_AD_ACCOUNT_SUFFIX', null),
  'username'                 => env('AUTH_AD_USERNAME', null),
  'password'                 => env('AUTH_AD_PASSWORD', null),
  'base_dn'                  => env('AUTH_AD_BASE_DN', 'dc=mydomain,dc=local'),
  'hosts'                    => env('AUTH_AD_HOSTS', []),
  'port'                     => env('AUTH_AD_PORT', 389),
  'follow_referrals'         => env('AUTH_AD_FOLLOW_REFERRALS', false),
  'use_ssl'                  => env('AUTH_AD_USE_SSL', false),
  'use_tls'                  => env('AUTH_AD_USE_TLS', false),
  'version'                  => env('AUTH_AD_VERSION', 3),
  'timeout'                  => env('AUTH_AD_TIMEOUT', 5),
  'mr_allowed_users'         => env('AUTH_AD_ALLOWED_USERS', []),
  'mr_allowed_groups'        => env('AUTH_AD_ALLOWED_GROUPS', []),
  'mr_recursive_groupsearch' => env('AUTH_AD_RECURSIVE_GROUPSEARCH', false),
];
