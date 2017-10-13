# SAML Authentication

MunkiReport uses OneLogin's SAML PHP Toolkit. This is a straightforward, modern library that is easy to integrate with MunkiReport.

## Configuration

To configure MunkiReport for SAML authentication, you need at least add the following to ``$config.php`:

```php
$conf['auth']['auth_saml'] = [
    'sp' => [
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    ],
    'idp' => [
        'entityId' => 'https://app.onelogin.com/saml/metadata/707308',
        'singleSignOnService' => ['url' => 'https://vrije-universiteit-amsterdam-dev.onelogin.com/trust/saml2/http-post/sso/707308'],
        'singleLogoutService' => ['url' => 'https://vrije-universiteit-amsterdam-dev.onelogin.com/trust/saml2/http-redirect/slo/707308'],
    ],
    'forceAuthn' => false,
];
```

Replace the values with the correct values for your env.

* sp - This is the Service Provider array. You should only add the `NameIDFormat` here. MunkiReport will automatically fill in the correct endpoints (metadata, acs and sls)
* idp - This is the Identity Provider array. You need to add the proper connection details for your IDp.
* entityId - URI to the IDp metadata
* singleSignOnService - Array containing one or more URIs to the Single Sign On endpoints of the IDp.
* singleLogoutService - Array containing one or more URIs to the Single Sign Out endpoints of the IDp.
* forceAuthn - boolean Force authentication allows you to force re-authentication of users even if the user has a SSO session at the IdP.

## Metadata

The Identity Provider needs some information from the MunkiReport SAML Service. You can generate this via this endpoint:

```
https://your-munkireport-server/index.php?/auth/saml/metadata
```

## Attribute Mapping

MunkiReport needs to know which attributes to map to `user` and to `groups`. You can add your own attribute mapping to `config.php`:

```php
$conf['auth']['auth_saml']['attr_mapping'] = [
    'memberOf' => 'groups',
    'User.email' => 'user',
];
```

## Authorization

You can handle authorization in the IDp by only allowing users and or groups to authenticate for MunkiReport. But you can also handle authorization in the SAML configuration:

```php
$conf['auth']['auth_saml']['mr_allowed_users'] = ['your_username', 'another_user'];
$conf['auth']['auth_saml']['mr_allowed_groups'] = ['admingroup'];
```

## More information/settings

See https://github.com/onelogin/php-saml#settings
