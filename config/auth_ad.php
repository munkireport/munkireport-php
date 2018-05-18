<?php

return [
  'account_suffix'           => getenv_default('AUTH_AD_ACCOUNT_SUFFIX', '@mydomain.local'),
  'base_dn'                  => getenv_default('AUTH_AD_BASE_DN', 'dc=>mydomain,dc=>local'),
  'domain_controllers'       => getenv_default('AUTH_AD_DOMAIN_CONTROLLERS', [], 'array'),
  'admin_username'           => getenv_default('AUTH_AD_ADMIN_USERNAME', ''),
  'admin_password'           => getenv_default('AUTH_AD_ADMIN_PASSWORD', ''),
  'schema'                   => getenv_default('AUTH_AD_SCHEMA', 'ActiveDirectory'),
  'mr_allowed_users'         => getenv_default('AUTH_AD_ALLOWED_USERS', [], 'array'),
  'mr_allowed_groups'        => getenv_default('AUTH_AD_ALLOWED_GROUPS', [], 'array'),
  'mr_recursive_groupsearch' => getenv_default('AUTH_AD_RECURSIVE_GROUPSEARCH', false, 'bool'),
];
