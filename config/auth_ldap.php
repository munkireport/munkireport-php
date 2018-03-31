<?php

return [
  'server'            => getenv_default('AUTH_LDAP_SERVER', 'ldap.server.local'), 
  'usertree'          => getenv_default('AUTH_LDAP_USER_BASE', 'uid=%{user},cn=users,dc=server,dc=local'), 
  'grouptree'         => getenv_default('AUTH_LDAP_GROUP_BASE', 'cn=groups,dc=server,dc=local'), 
  'mr_allowed_users'  => getenv_default('AUTH_LDAP_ALLOWED_USERS', [], 'array'), 
  'mr_allowed_groups' => getenv_default('AUTH_LDAP_ALLOWED_GROUPS', [], 'array'), 

  'userfilter'        => getenv_default('AUTH_LDAP_USER_FILTER', '(&(uid=%{user})(objectClass=posixAccount))'), 
  'groupfilter'       => getenv_default('AUTH_LDAP_GROUP_FILTER', '(&(objectClass=posixGroup)(memberUID=%{uid}))'), 
  'port'              => getenv_default('AUTH_LDAP_PORT', 389, 'int'), 
  'version'           => getenv_default('AUTH_LDAP_VERSION', 3, 'int'), 
  'starttls'          => getenv_default('AUTH_LDAP_USE_STARTTLS', false, 'bool'), 
  'referrals'         => getenv_default('AUTH_LDAP_FOLLOW_REFERRALS', false, 'bool'), 
  'deref'             => getenv_default('AUTH_LDAP_DEREF', LDAP_DEREF_NEVER), 

  'binddn'            => getenv_default('AUTH_LDAP_BIND_DN', ''), 
  'bindpw'            => getenv_default('AUTH_LDAP_BIND_PASSWORD', ''), 
  'userscope'         => getenv_default('AUTH_LDAP_USER_SCOPE', 'sub'), 
  'groupscope'        => getenv_default('AUTH_LDAP_GROUP_SCOPE', 'sub'), 
  'groupkey'          => getenv_default('AUTH_LDAP_GROUP_KEY', 'cn'), 
  'debug'             => getenv_default('AUTH_LDAP_DEBUG', false, 'bool'), 
];

