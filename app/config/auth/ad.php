<?php

return [
  'account_suffix'           => env('AUTH_AD_ACCOUNT_SUFFIX', NULL),
  'base_dn'                  => env('AUTH_AD_BASE_DN', 'dc=mydomain,dc=local'),
  'hosts'                    => env('AUTH_AD_HOSTS', []),
  'schema'                   => env('AUTH_AD_SCHEMA', 'ActiveDirectory'),
  'mr_allowed_users'         => env('AUTH_AD_ALLOWED_USERS', []),
  'mr_allowed_groups'        => env('AUTH_AD_ALLOWED_GROUPS', []),
  'mr_recursive_groupsearch' => env('AUTH_AD_RECURSIVE_GROUPSEARCH', false),
];
