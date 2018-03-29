<?php

return [
  'server'            => getenv_default('AUTH_LDAP_SERVER', 'ldap.server.local'), // One or more servers separated by commas.
  'usertree'          => getenv_default('AUTH_LDAP_USER_BASE', 'uid=%{user},cn=users,dc=server,dc=local'), // Where to find the user accounts.
  'grouptree'         => getenv_default('AUTH_LDAP_GROUP_BASE', 'cn=groups,dc=server,dc=local'), // The search base for group objects.
  'mr_allowed_users'  => getenv_default('AUTH_LDAP_ALLOWED_USERS', [], 'array'), // For user based access, fill in users.
  'mr_allowed_groups' => getenv_default('AUTH_LDAP_ALLOWED_GROUPS', [], 'array'), // For group based access, fill in groups.

  'userfilter'        => getenv_default('AUTH_LDAP_USER_FILTER', '(&(uid=%{user})(objectClass=posixAccount))'), // LDAP filter to search for user accounts.
  'groupfilter'       => getenv_default('AUTH_LDAP_GROUP_FILTER', '(&(objectClass=posixGroup)(memberUID=%{uid}))'), // LDAP filter to search for groups.
  'port'              => getenv_default('AUTH_LDAP_PORT', 389, 'int'), // LDAP port.
  'version'           => getenv_default('AUTH_LDAP_VERSION', 3, 'int'), // Use LDAP version 1, 2 or 3.
  'starttls'          => getenv_default('AUTH_LDAP_USE_STARTTLS', false, 'bool'), // Set to TRUE to use TLS.
  'referrals'         => getenv_default('AUTH_LDAP_FOLLOW_REFERRALS', false, 'bool'), // Set to TRUE to follow referrals.
  'deref'             => getenv_default('AUTH_LDAP_DEREF', LDAP_DEREF_NEVER), // How to dereference aliases. See http://php.net/ldap_search.

  'binddn'            => getenv_default('AUTH_LDAP_BIND_DN', ''), // Optional bind DN.
  'bindpw'            => getenv_default('AUTH_LDAP_BIND_PASSWORD', ''), // Optional bind password.
  'userscope'         => getenv_default('AUTH_LDAP_USER_SCOPE', 'sub'), // Limit search scope to sub, one or base.
  'groupscope'        => getenv_default('AUTH_LDAP_GROUP_SCOPE', 'sub'), // Limit search scope to sub, one or base.
  'groupkey'          => getenv_default('AUTH_LDAP_GROUP_KEY', 'cn'), // The key that is used to determine group membership.
  'debug'             => getenv_default('AUTH_LDAP_DEBUG', false, 'bool'), // Set to TRUE to debug LDAP.
];

