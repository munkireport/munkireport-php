# What changed in the Laravel refactor? Non-Developer Edition #

A lot has changed under the hood in MunkiReport to make sure that it keeps up with the latest technologies
and platforms. Let's have a look at what the basic changes are:

* **Authentication completely swapped for Laravel implementation**: This may mean that you have to look at your
  configuration and see whether the options available have changed. There might be a better option available or the
  behaviour might be different to what you are used to.
* **A boatload of under the hood changes, which make no difference to you**: I can explain this by just saying
  the team won't have to focus on the engine so much, and they can focus on the really mac specific stuff! Sometimes
  this will mean we can give you a new feature without writing much code.

## Authentication Changes ##

For backwards compatibility, many of the keys described in the wiki 
article on [Authentication](https://github.com/munkireport/munkireport-php/wiki/Server-Configuration#Authentication)
are still valid, but they work a different way.

You may still provide authentication methods like this, as part of the app environment or `.env` file:

```dotenv
AUTH_METHODS="SAML,NETWORK,AD"
```

Authentication methods available to users will be a combination of all the methods you asked for, except if you
ask for **NOAUTH**, which means everything else gets ignored.

### LOCAL Authentication ###

The local authentication mechanism has changed **SUBSTANTIALLY**.

Compare the features of MunkiReport Local and Laravel Local Authentication:

| AUTH_LOCAL features | MunkiReport   | Laravel    |
|:------------------- |:-------------:| :---------:|
| .yml users          | yes           | no         |
| config based users  | yes           | no         |
| database users      | no            | yes        |
| sign-up page        | no            | yes        |
| password reset      | no            | yes        |

#### New Stuff ####

* **Sign up page** Allows you to have a registration mechanism where people apply for a login themselves.
  E-mail verification obviously only works if you configure an SMTP server.
* **Password reset** If you have e-mail configured, allows you to provide password resets for users which are logged
  in using LOCAL authentication.
* **Local DB user creation from the CLI**, for example:

      php please user:create
  
      Name?:
      > admin
      
      Email address?:
      > admin@localhost
      
      Password?:
      >
      
      User saved

#### Removed ####

* Unfortunately there is no support for `.yml` defined users in Laravel, although we could offer a migration tool later
on if this becomes a huge concern.
* Generating users from `/auth/create_local_user` will not be supported, you will have
  to use `please create:user` *OR* Register with a valid e-mail address.
  
### (Deprecated) NETWORK Authentication ###

There is no such thing as "NETWORK" authentication in this release. It has been deprecated and will be replaced
by an IP/Host firewalling concept which is separate from authentication. Why? because the source IP does not really
identify you as a particular user, so it doesn't authenticate your identity.

### LDAP / Active Directory Authentication ###

This functionality is almost the same, except for some variables which will become renamed in future.

For now you can use the old name _OR_ the new name.

The table below demonstrates how the configuration will be renamed in future

| Current                       | Next Version                                                  |
| :---------------------------- | :------------------------------------------------------------ |
| AUTH_AD_HOSTS                 | LDAP_HOSTS                                                    |
| AUTH_AD_BASE_DN               | LDAP_BASE_DN                                                  |
| AUTH_AD_SCHEMA                | (N/A) statically set to Adldap\Schemas\ActiveDirectory::class |
| AUTH_AD_ACCOUNT_PREFIX        | LDAP_ACCOUNT_PREFIX                                           |
| AUTH_AD_ACCOUNT_SUFFIX        | LDAP_ACCOUNT_SUFFIX                                           |
| AUTH_AD_USERNAME              | LDAP_USERNAME                                                 |
| AUTH_AD_PASSWORD              | LDAP_PASSWORD                                                 |
| AUTH_AD_PORT                  | LDAP_PORT                                                     |
| AUTH_AD_USE_SSL               | LDAP_USE_SSL                                                  |
| AUTH_AD_USE_TLS               | LDAP_USE_TLS                                                  |
| AUTH_AD_VERSION               | (N/A)                                                         |
| AUTH_AD_TIMEOUT               | LDAP_TIMEOUT                                                  |
| AUTH_AD_FOLLOW_REFERRALS      | (N/A)                                                         |
| AUTH_AD_ALLOWED_USERS         | AUTH_AD_ALLOWED_USERS provided for backwards compat.          |
| AUTH_AD_ALLOWED_GROUPS        | AUTH_AD_ALLOWED_GROUPS provided for backwards compat.         |
| AUTH_AD_RECURSIVE_GROUPSEARCH | (N/A)                                                         |
| (N/A)                         | LDAP_LOGGING                                                  |
| (N/A)                         | LDAP_LOGIN_FALLBACK                                           |
| (N/A)                         | LDAP_PASSWORD_SYNC                                            |


For even more information and customisation of the ldap config, see [config/ldap.php](../../config/ldap.php) and
[config/ldap_auth.php](../../config/ldap_auth.php).

#### Additions ####

The only way that the behaviour is different, is the introduction of the `LDAP_LOGIN_FALLBACK` configuration item,
which allows you to let LDAP users log in, even when Active Directory / LDAP is not available. You should consider
that this means MunkiReport will be caching credentials (in an encrypted way), so if this is not suitable for your
use case, do not enable the fallback option.

### SAML Authentication ###

The same framework is used under the hood but the options are presented a little bit differently.

The configuration variables will change names, similar to how AD/LDAP changed. See the table below

| Current                       | Next Version                                                  |
| :---------------------------- | :------------------------------------------------------------ |
| AUTH_SAML_SP_NAME_ID_FORMAT   | **(default)** `urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress`, see config file for change |
| AUTH_SAML_IDP_ENTITY_ID       | SAML2_DEFAULT_IDP_ENTITYID                                    |
| AUTH_SAML_IDP_SSO_URL         | SAML2_DEFAULT_IDP_SSO_URL                                     |
| AUTH_SAML_IDP_SLO_URL         | SAML2_DEFAULT_IDP_SL_URL                                      |
| AUTH_SAML_IDP_X509CERT        | SAML2_DEFAULT_IDP_x509                                        |
| AUTH_SAML_GROUP_ATTR          | **(unchanged)** AUTH_SAML_GROUP_ATTR                          |
| (N/A)                         | SAML2_DEFAULT_IDP_DISPLAYNAME (login button caption)          |

There are far more options than this. If you are interested, please refer to the saml2 configuration file(s) located
in [config/saml2](../../config/saml2).

#### Custom SAML Providers ####

If you have requirements that arent met by the default configuration, (which should cover 90% of cases), you can create a custom SAML config by
duplicating the example config file, [config/saml2/examples/template_idp_settings.php](../../config/saml2/examples/template_idp_settings.php),
and adjusting to your needs.

When you create a custom provider like this, the config variables have your new custom name added as a prefix,
for example: `SAML2_MYSAMLPROVIDER_*`, if you specified "mysamlprovider" as the IdP name.

## Authorization, Roles and Groups Changes ##

Usage of `ROLES_ADMIN`, `GROUPS_ADMIN_USERS`, etc.. TBD

### Role mapping LOCAL users ###

### Role mapping LDAP membership ###

### Role mapping SAML claims ###


## Configuration General Changes ##

