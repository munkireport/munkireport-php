<?php

return [
  'schema'                   => mr_env('AUTH_AD_SCHEMA', 'ActiveDirectory'),
  'account_prefix'           => mr_env('AUTH_AD_ACCOUNT_PREFIX', NULL),
  'account_suffix'           => mr_env('AUTH_AD_ACCOUNT_SUFFIX', NULL),
  'username'                 => mr_env('AUTH_AD_USERNAME', NULL),
  'password'                 => mr_env('AUTH_AD_PASSWORD', NULL),
  'base_dn'                  => mr_env('AUTH_AD_BASE_DN', 'dc=mydomain,dc=local'),
  'hosts'                    => mr_env('AUTH_AD_HOSTS', []),
  'port'                     => mr_env('AUTH_AD_PORT', 389),
  'follow_referrals'         => mr_env('AUTH_AD_FOLLOW_REFERRALS', false),
  'use_ssl'                  => mr_env('AUTH_AD_USE_SSL', false),
  'use_tls'                  => mr_env('AUTH_AD_USE_TLS', false),
  'version'                  => mr_env('AUTH_AD_VERSION', 3),
  'timeout'                  => mr_env('AUTH_AD_TIMEOUT', 5),
  'mr_allowed_users'         => mr_env('AUTH_AD_ALLOWED_USERS', []),
  'mr_allowed_groups'        => mr_env('AUTH_AD_ALLOWED_GROUPS', []),
  'mr_recursive_groupsearch' => mr_env('AUTH_AD_RECURSIVE_GROUPSEARCH', false),
];
