# v6 Upgrade Guide #

## Deprecated Features ##

### NOAUTH no longer available ###

It was not feasible to make the `NOAUTH` authentication mechanism work with the framework authorization mechanisms, so
it was removed from this release. Currently there is no alternative.

### Local user creation via /auth/create_local_user no longer available ###

You cannot use this URL to generate a local user YAML file anymore, because the local user yaml feature has been removed.

If you need to create a local user interactively, you could use the `php please make:user` command, or register via
the sign up link (if sign ups are enabled).

### Local user .yaml no longer available ###

Providing local users via the `local/users` directory is no longer supported.

### Removed or Irrelevant Config Items ###

- `INDEX_PAGE`: since the framework expects URL rewriting, this is no longer relevant.
- `URI_PROTOCOL`: no longer needed.
- `SUBDIRECTORY`: include the subdirectory as part of your `APP_URL`.

### ReCaptcha replaced by NoCaptcha ###

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


## Client Changes ##

All client scripts are upgraded to Python 3 and the client will now be installed with a symlink to the 
best available Python 3 interpreter.

If you don't have python 3 installed on your fleet, you should consider adding the package to your deployment
system (in order of preference):

- [Macadmins Python](https://github.com/macadmins/python/releases)
- [Munki Python, included in Munki](https://github.com/munki/munki/releases)

If you must use another distribution (such as the python.org package), you will need to manually install [PyObjC](https://pypi.org/project/pyobjc/) for
most client scripts to work.

## Modules Changes ##

A new type of module structure is available that uses [Laravel Packages](https://laravel.com/docs/8.x/packages)
which we will refer to as the v6 module spec.

The current module style still works through a compatibility layer, so no changes are needed immediately
to make older modules work.


