<?php

return [
  'server'            => env('AUTH_LDAP_SERVER', 'ldap.server.local'), 
  'usertree'          => env('AUTH_LDAP_USER_BASE', 'uid=%{user},cn=users,dc=server,dc=local'), 
  'grouptree'         => env('AUTH_LDAP_GROUP_BASE', 'cn=groups,dc=server,dc=local'), 
  'mr_allowed_users'  => env('AUTH_LDAP_ALLOWED_USERS', []), 
  'mr_allowed_groups' => env('AUTH_LDAP_ALLOWED_GROUPS', []), 

  'userfilter'        => env('AUTH_LDAP_USER_FILTER', '(&(uid=%{user})(objectClass=posixAccount))'), 
  'groupfilter'       => env('AUTH_LDAP_GROUP_FILTER', '(&(objectClass=posixGroup)(memberUID=%{uid}))'), 
  'port'              => env('AUTH_LDAP_PORT', 389), 
  'version'           => env('AUTH_LDAP_VERSION', 3), 
  'starttls'          => env('AUTH_LDAP_USE_STARTTLS', false), 
  'referrals'         => env('AUTH_LDAP_FOLLOW_REFERRALS', false), 
  'deref'             => env('AUTH_LDAP_DEREF', LDAP_DEREF_NEVER), 

  'binddn'            => env('AUTH_LDAP_BIND_DN', ''), 
  'bindpw'            => env('AUTH_LDAP_BIND_PASSWORD', ''), 
  'userscope'         => env('AUTH_LDAP_USER_SCOPE', 'sub'), 
  'groupscope'        => env('AUTH_LDAP_GROUP_SCOPE', 'sub'), 
  'groupkey'          => env('AUTH_LDAP_GROUP_KEY', 'cn'), 
  'debug'             => env('AUTH_LDAP_DEBUG', false), 
];

