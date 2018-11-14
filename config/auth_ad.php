<?php

return [
  'account_suffix'           => env('AUTH_AD_ACCOUNT_SUFFIX', NULL),
  'base_dn'                  => env('AUTH_AD_BASE_DN', 'dc=mydomain,dc=local'),
  'domain_controllers'       => env('AUTH_AD_DOMAIN_CONTROLLERS', []),
  'admin_username'           => env('AUTH_AD_ADMIN_USERNAME', ''),
  'admin_password'           => env('AUTH_AD_ADMIN_PASSWORD', ''),
  'schema'                   => env('AUTH_AD_SCHEMA', 'ActiveDirectory'),
  'mr_allowed_users'         => env('AUTH_AD_ALLOWED_USERS', []),
  'mr_allowed_groups'        => env('AUTH_AD_ALLOWED_GROUPS', []),
  'mr_recursive_groupsearch' => env('AUTH_AD_RECURSIVE_GROUPSEARCH', false),
];
