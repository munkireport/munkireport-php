# v5.x to v6.0 Upgrade Guide #

## Pre-requisites ##

* You have a backup of your database.
* You have a backup of your configuration (.env/config/*.php etc)

## Configuration Changes ##

### Renames ###

A number of configuration variables have been renamed. In most cases the old name is still available
but will be removed:

| Old Value              | New Value           | Old Default      | New Default              |
| :--------------------- | :------------------ | :--------------- | :----------------------- |
| ENCRYPTION_KEY         | APP_KEY             | (empty)          | (generated)              |
| CONNECTION_DRIVER      | DB_CONNECTION       | sqlite           | sqlite                   |
| CONNECTION_DATABASE    | DB_DATABASE         | app/db/db.sqlite | database/database.sqlite |
| CONNECTION_HOST        | DB_HOST             | 127.0.0.1        | localhost                |
| CONNECTION_PORT        | DB_PORT             | 3306             | 3306                     |
| CONNECTION_USERNAME    | DB_USERNAME         | root             | root                     |
| CONNECTION_PASSWORD    | DB_PASSWORD         | (empty)          | (empty)                  |
| CONNECTION_CHARSET     | unchanged           |                  |                          |
| CONNECTION_COLLATION   | unchanged           |                  |                          |
| CONNECTION_STRICT      | unchanged           |                  |                          |
| CONNECTION_ENGINE      | unchanged           |                  |                          |
| AUTH_AD_ACCOUNT_SUFFIX | LDAP_ACCOUNT_SUFFIX |                  | (empty)                  |
| AUTH_AD_BASE_DN        | LDAP_BASE_DN        |
| AUTH_AD_HOSTS          | LDAP_HOSTS          |
| AUTH_AD_USERNAME       | LDAP_USERNAME       |
| AUTH_AD_PASSWORD       | LDAP_PASSWORD       |
| AUTH_AD_USE_SSL        | LDAP_USE_SSL        |
| AUTH_AD_PORT           | LDAP_PORT           |
| AUTH_AD_TIMEOUT        | LDAP_TIMEOUT        |
| SITENAME               | APP_NAME            | MunkiReport      | MunkiReport              |


## Authentication Changes ##

### Local Authentication ###

The upgraded authentication system provides local, database stored, user authentication that is 
a bit different from MunkiReport v5.

- Supports password recovery via e-mail.
- Supports user sign-up.
- Does not support .yml files.

### LDAP(S) Authentication, including Active Directory ###

This feature still uses adldap2 so it has the same feature set, however the configuration names have 
changed in some places.

### SAML Authentication ###

OneLogin PHP SAML Toolkit is still used under the hood, but another package is providing the configuration,
so a number of options have been renamed.

You also have the option of configuring multiple SAML IdP's, although this scenario has not been tested.

## Authorization Changes ##

### Roles can now accept username or e-mail address ###

Because we now expect the user principal to be an e-mail address, the roles config can accept e-mail addresses as
well as usernames. It will still respect usernames if you have some users that are not migrated to e-mail addresses.


## Client Changes (Since v5.8.0) ##

All client scripts are upgraded to Python 3 and the client will now be installed with a symlink to the 
best available Python 3 interpreter.

If you don't have python 3 installed on your fleet, you should consider adding the package to your deployment
system (in order of preference):

- [Macadmins Python](https://github.com/macadmins/python/releases)
- [Munki Python, included in Munki](https://github.com/munki/munki/releases)

If you must use another distribution (such as the python.org package), you will need to manually install [PyObjC](https://pypi.org/project/pyobjc/) for
most client scripts to work.

## Modules Changes ##

A new type of module structure is available that uses [Laravel Packages](https://laravel.com/docs/10.x/packages)
which we will refer to as the v6 module spec.

The current module style still works through a compatibility layer, so no changes are needed immediately
to make older modules work.

## New Features ##

### Application Logging ###

All application errors are logged to the default application log, which lives in `storage/logs/laravel.log`.

As a module developer, you can enable the deprecation log to see what parts of your module are using outdated API.
The deprecation log will be stored at `storage/logs/deprecations.log`.

### API Keys ###

The MunkiReport interface now provides you with a way to manage API Keys that you can use to interact with the REST API
or GraphQL API without using your user credentials. This is the preferred way to access the API, 
and the *only* way to interact with the API if you are signing in with SSO such as Entra ID or Okta.

### OpenID Connect (OIDC) via Laravel Socialite ###

MunkiReport-PHP now has support for OpenID Connect.

At this stage, we only test with Entra ID as the identity platform, but you are welcome to try others.

You can enable OIDC sign-in for Entra ID by including this environment variable in .env or the web application environment:

    AUTH_METHODS="OIDC"

You can still provide a fallback to local database authentication (in case Entra ID is not available) like so:

    AUTH_METHODS="OIDC,LOCAL"

