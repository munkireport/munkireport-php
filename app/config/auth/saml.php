<?php

return [
  'sp' => [
    'NameIDFormat' => mr_env('AUTH_SAML_SP_NAME_ID_FORMAT', 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'),
    'entityId' => mr_env('AUTH_SAML_SP_ENTITY_ID', ''),
    'x509cert' => mr_env('AUTH_SAML_SP_X509CERT', ''),
    'privateKey' => mr_env('AUTH_SAML_SP_PRIVATEKEY', ''),
  ],
  'idp' => [
    'entityId' => mr_env('AUTH_SAML_IDP_ENTITY_ID', 'https://app.onelogin.com/saml/metadata/xxxx'),
    'singleSignOnService' => [
      'url' => mr_env('AUTH_SAML_IDP_SSO_URL', 'https://yourorg.onelogin.com/trust/saml2/http-post/sso/xxxx'),
      'binding' => mr_env('AUTH_SAML_IDP_SSO_BINDING', ''),
    ],
    'singleLogoutService' => [
      'url' => mr_env('AUTH_SAML_IDP_SLO_URL', 'https://yourorg.onelogin.com/trust/saml2/http-redirect/slo/xxxx'),
      'binding' => mr_env('AUTH_SAML_IDP_SLO_BINDING', ''),
    ],
    'x509cert' => mr_env('AUTH_SAML_IDP_X509CERT'),
  ],
  'attr_mapping' => [
    'user' => mr_env('AUTH_SAML_USER_ATTR', 'User.email'),
    'groups' => mr_env('AUTH_SAML_GROUP_ATTR', ['memberOf']),
  ],
  'disable_sso'       => mr_env('AUTH_SAML_DISABLE_SSO', false),
  'debug'             => mr_env('AUTH_SAML_DEBUG', false),
  'security'          => [
    'nameIdEncrypted'            => mr_env('AUTH_SAML_SECURITY_NAME_ID_ENCRYPTED', false),
    'authnRequestsSigned'        => mr_env('AUTH_SAML_SECURITY_AUTHN_REQUESTS_SIGNED', false),
    'logoutRequestSigned'        => mr_env('AUTH_SAML_SECURITY_LOGOUT_REQUEST_SIGNED', false),
    'logoutResponseSigned'       => mr_env('AUTH_SAML_SECURITY_LOGOUT_RESPONSE_SIGNED', false),
    'signMetadata'               => mr_env('AUTH_SAML_SECURITY_SIGN_METADATA', false),
    'wantMessagesSigned'         => mr_env('AUTH_SAML_SECURITY_WANT_MESSAGES_SIGNED', false),
    'wantAssertionsEncrypted'    => mr_env('AUTH_SAML_SECURITY_WANT_ASSERTIONS_ENCRYPTED', false),
    'wantAssertionsSigned'       => mr_env('AUTH_SAML_SECURITY_WANT_ASSERTIONS_SIGNED', false),
    'wantNameId'                 => mr_env('AUTH_SAML_SECURITY_WANT_NAME_ID', true),
    'wantNameIdEncrypted'        => mr_env('AUTH_SAML_SECURITY_WANT_NAME_ID_ENCRYPTED', false),
    'requestedAuthnContext'      => mr_env('AUTH_SAML_SECURITY_REQUESTED_AUTHN_CONTEXT', true),
    'wantXMLValidation'          => mr_env('AUTH_SAML_SECURITY_WANT_XML_VALIDATION', true),
    'relaxDestinationValidation' => mr_env('AUTH_SAML_SECURITY_RELAX_DESTINATION_VALIDATION', false),
    'signatureAlgorithm'         => mr_env('AUTH_SAML_SECURITY_SIGNATURE_ALGORITHM', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256'),
    'digestAlgorithm'            => mr_env('AUTH_SAML_SECURITY_DIGEST_ALGORITHM', 'http://www.w3.org/2001/04/xmlenc#sha256'),
    'lowercaseUrlencoding'       => mr_env('AUTH_SAML_SECURITY_LOWERCASE_URLENCODING', false),
  ],
  'munkireport' => [
    'mr_allowed_users'  => mr_env('AUTH_SAML_ALLOWED_USERS', []),
    'mr_allowed_groups' => mr_env('AUTH_SAML_ALLOWED_GROUPS', []),
    'cert_directory' => mr_env('AUTH_SAML_CERT_DIR', local_conf('certs/')),
  ],
];
