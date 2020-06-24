<?php

return [
  'server'            => mr_env('AUTH_LDAP_SERVER', 'ldap.server.local'),
  'usertree'          => mr_env('AUTH_LDAP_USER_BASE', 'uid=%{user},cn=users,dc=server,dc=local'),
  'grouptree'         => mr_env('AUTH_LDAP_GROUP_BASE', 'cn=groups,dc=server,dc=local'),
  'mr_allowed_users'  => mr_env('AUTH_LDAP_ALLOWED_USERS', []),
  'mr_allowed_groups' => mr_env('AUTH_LDAP_ALLOWED_GROUPS', []),

  'userfilter'        => mr_env('AUTH_LDAP_USER_FILTER', '(&(uid=%{user})(objectClass=posixAccount))'),
  'groupfilter'       => mr_env('AUTH_LDAP_GROUP_FILTER', '(&(objectClass=posixGroup)(memberUID=%{uid}))'),
  'port'              => mr_env('AUTH_LDAP_PORT', 389),
  'version'           => mr_env('AUTH_LDAP_VERSION', 3),
  'starttls'          => mr_env('AUTH_LDAP_USE_STARTTLS', false),
  'referrals'         => mr_env('AUTH_LDAP_FOLLOW_REFERRALS', false),
  'deref'             => mr_env('AUTH_LDAP_DEREF', LDAP_DEREF_NEVER),

  'binddn'            => mr_env('AUTH_LDAP_BIND_DN', ''),
  'bindpw'            => mr_env('AUTH_LDAP_BIND_PASSWORD', ''),
  'userscope'         => mr_env('AUTH_LDAP_USER_SCOPE', 'sub'),
  'groupscope'        => mr_env('AUTH_LDAP_GROUP_SCOPE', 'sub'),
  'groupkey'          => mr_env('AUTH_LDAP_GROUP_KEY', 'cn'),
  'debug'             => mr_env('AUTH_LDAP_DEBUG', false),
];

