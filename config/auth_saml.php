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
  'mr_allowed_users'  => getenv_default('AUTH_SAML_ALLOWED_USERS', [], 'array'),
  'mr_allowed_groups' => getenv_default('AUTH_SAML_ALLOWED_GROUPS', [], 'array'),
  'disable_sso'       => getenv_default('AUTH_SAML_DISABLE_SSO', false, 'bool'),
  'debug'             => getenv_default('AUTH_SAML_DEBUG', false, 'bool'),
  'security'          => [
    'nameIdEncrypted'            => getenv_default('AUTH_SAML_SECURITY_NAME_ID_ENCRYPTED', false, 'bool'),
    'authnRequestsSigned'        => getenv_default('AUTH_SAML_SECURITY_AUTHN_REQUESTS_SIGNED', false, 'bool'),
    'logoutRequestSigned'        => getenv_default('AUTH_SAML_SECURITY_LOGOUT_REQUEST_SIGNED', false, 'bool'),
    'logoutResponseSigned'       => getenv_default('AUTH_SAML_SECURITY_LOGOUT_RESPONSE_SIGNED', false, 'bool'),
    'signMetadata'               => getenv_default('AUTH_SAML_SECURITY_SIGN_METADATA', false, 'bool'),
    'wantMessagesSigned'         => getenv_default('AUTH_SAML_SECURITY_WANT_MESSAGES_SIGNED', false, 'bool'),
    'wantAssertionsEncrypted'    => getenv_default('AUTH_SAML_SECURITY_WANT_ASSERTIONS_ENCRYPTED', false, 'bool'),
    'wantAssertionsSigned'       => getenv_default('AUTH_SAML_SECURITY_WANT_ASSERTIONS_SIGNED', false, 'bool'),
    'wantNameId'                 => getenv_default('AUTH_SAML_SECURITY_WANT_NAME_ID', true, 'bool'),
    'wantNameIdEncrypted'        => getenv_default('AUTH_SAML_SECURITY_WANT_NAME_ID_ENCRYPTED', false, 'bool'),
    'requestedAuthnContext'      => getenv_default('AUTH_SAML_SECURITY_REQUESTED_AUTHN_CONTEXT', true, 'bool'),
    'wantXMLValidation'          => getenv_default('AUTH_SAML_SECURITY_WANT_XML_VALIDATION', true, 'bool'),
    'relaxDestinationValidation' => getenv_default('AUTH_SAML_SECURITY_RELAX_DESTINATION_VALIDATION', false, 'bool'),
    'signatureAlgorithm'         => getenv_default('AUTH_SAML_SECURITY_SIGNATURE_ALGORITHM', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256'),
    'digestAlgorithm'            => getenv_default('AUTH_SAML_SECURITY_DIGEST_ALGORITHM', 'http://www.w3.org/2001/04/xmlenc#sha256'),
    'lowercaseUrlencoding'       => getenv_default('AUTH_SAML_SECURITY_LOWERCASE_URLENCODING', false, 'bool'),
  ],
];
