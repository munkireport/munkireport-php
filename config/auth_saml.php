<?php

return [
  'sp' => [
    'NameIDFormat' => getenv_default('AUTH_SAML_SP_NAME_ID_FORMAT', 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'),
    'entityId' => getenv_default('AUTH_SAML_SP_ENTITY_ID', ''),
  ],
  'idp' => [
    'entityId' => getenv_default('AUTH_SAML_IDP_ENTITY_ID', 'https://app.onelogin.com/saml/metadata/xxxx'),
    'singleSignOnService' => [
      'url' => getenv_default('AUTH_SAML_IDP_SSO_URL', 'https://yourorg.onelogin.com/trust/saml2/http-post/sso/xxxx'),
    ],
    'singleLogoutService' => [
      'url' => getenv_default('AUTH_SAML_IDP_SLO_URL', 'https://yourorg.onelogin.com/trust/saml2/http-redirect/slo/xxxx'),
    ],
    'x509cert' => getenv_default('AUTH_SAML_IDP_X509CERT'),
  ],
  'attr_mapping' => [
    getenv_default('AUTH_SAML_GROUP_ATTR', 'memberOf') => 'groups',
    getenv_default('AUTH_SAML_USER_ATTR', 'User.email') => 'user',
  ],
  'mr_allowed_users' => getenv_default('AUTH_SAML_ALLOWED_USERS', [], 'array'),
  'mr_allowed_groups' => getenv_default('AUTH_SAML_ALLOWED_GROUPS', [], 'array'),
  'disable_sso' => getenv_default('AUTH_SAML_DISABLE_SSO', false, 'bool'),
  'debug' => getenv_default('AUTH_SAML_DEBUG', false, 'bool'),
];
