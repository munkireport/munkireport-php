<?php

return [
  'sp' => [
    'NameIDFormat' => env('AUTH_SAML_SP_NAME_ID_FORMAT', 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'),
    'entityId' => env('AUTH_SAML_SP_ENTITY_ID', ''),
    'x509cert' => env('AUTH_SAML_SP_X509CERT', ''),
    'privateKey' => env('AUTH_SAML_SP_PRIVATEKEY', ''),
  ],
  'idp' => [
    'entityId' => env('AUTH_SAML_IDP_ENTITY_ID', 'https://app.onelogin.com/saml/metadata/xxxx'),
    'singleSignOnService' => [
      'url' => env('AUTH_SAML_IDP_SSO_URL', 'https://yourorg.onelogin.com/trust/saml2/http-post/sso/xxxx'),
      'binding' => env('AUTH_SAML_IDP_SSO_BINDING', ''),
    ],
    'singleLogoutService' => [
      'url' => env('AUTH_SAML_IDP_SLO_URL', 'https://yourorg.onelogin.com/trust/saml2/http-redirect/slo/xxxx'),
      'binding' => env('AUTH_SAML_IDP_SLO_BINDING', ''),
    ],
    'x509cert' => env('AUTH_SAML_IDP_X509CERT'),
  ],
  'attr_mapping' => [
    'user' => env('AUTH_SAML_USER_ATTR', 'User.email'),
    'groups' => env('AUTH_SAML_GROUP_ATTR', ['memberOf']),
  ],
  'disable_sso'       => env('AUTH_SAML_DISABLE_SSO', false),
  'debug'             => env('AUTH_SAML_DEBUG', false),
  'security'          => [
    'nameIdEncrypted'            => env('AUTH_SAML_SECURITY_NAME_ID_ENCRYPTED', false),
    'authnRequestsSigned'        => env('AUTH_SAML_SECURITY_AUTHN_REQUESTS_SIGNED', false),
    'logoutRequestSigned'        => env('AUTH_SAML_SECURITY_LOGOUT_REQUEST_SIGNED', false),
    'logoutResponseSigned'       => env('AUTH_SAML_SECURITY_LOGOUT_RESPONSE_SIGNED', false),
    'signMetadata'               => env('AUTH_SAML_SECURITY_SIGN_METADATA', false),
    'wantMessagesSigned'         => env('AUTH_SAML_SECURITY_WANT_MESSAGES_SIGNED', false),
    'wantAssertionsEncrypted'    => env('AUTH_SAML_SECURITY_WANT_ASSERTIONS_ENCRYPTED', false),
    'wantAssertionsSigned'       => env('AUTH_SAML_SECURITY_WANT_ASSERTIONS_SIGNED', false),
    'wantNameId'                 => env('AUTH_SAML_SECURITY_WANT_NAME_ID', true),
    'wantNameIdEncrypted'        => env('AUTH_SAML_SECURITY_WANT_NAME_ID_ENCRYPTED', false),
    'requestedAuthnContext'      => env('AUTH_SAML_SECURITY_REQUESTED_AUTHN_CONTEXT', true),
    'wantXMLValidation'          => env('AUTH_SAML_SECURITY_WANT_XML_VALIDATION', true),
    'relaxDestinationValidation' => env('AUTH_SAML_SECURITY_RELAX_DESTINATION_VALIDATION', false),
    'signatureAlgorithm'         => env('AUTH_SAML_SECURITY_SIGNATURE_ALGORITHM', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256'),
    'digestAlgorithm'            => env('AUTH_SAML_SECURITY_DIGEST_ALGORITHM', 'http://www.w3.org/2001/04/xmlenc#sha256'),
    'lowercaseUrlencoding'       => env('AUTH_SAML_SECURITY_LOWERCASE_URLENCODING', false),
  ],
  'munkireport' => [
    'mr_allowed_users'  => env('AUTH_SAML_ALLOWED_USERS', []),
    'mr_allowed_groups' => env('AUTH_SAML_ALLOWED_GROUPS', []),
    'cert_directory' => env('AUTH_SAML_CERT_DIR', local_conf('certs/')),
  ],
];
