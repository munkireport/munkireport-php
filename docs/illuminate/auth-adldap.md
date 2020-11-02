# Active Directory/LDAP Authentication #

## Configuration ##

### Connection (LDAP) ###

You can configure your LDAP/AD server connection in [config/ldap.php](../../config/ldap.php) or via the provided
environment variables, optionally in .env (recommended).

The available LDAP connection options are as follows, the options are listed with their *default* values.
*NOTE:* Not all options are listed here, just the ones required to get a basic setup working.

```dotenv
# A list of your Active Directory domain controllers, separated by a space character.
LDAP_HOSTS="corp-dc1.corp.acme.org corp-dc2.corp.acme.org"

# The LDAP port to use. Note: Microsoft are releasing a patch which disables simple binds on port 389, so consider
# using SSL/TLS binds on port 636.
LDAP_PORT=389

# The base distinguished name which will be used to search for users.
# Typically, this looks like your Active Directory domain name, but in a Distinguished Name format, so for an example
# If you had a Windows Active Directory domain called `corp.acme.org`, your Base DN would be: 
LDAP_BASE_DN="dc=corp,dc=acme,dc=org"

# The credentials of an account that will be able to search Active Directory for the authenticating user.
# This should _NOT_ be a Domain/Enterprise Administrator
LDAP_USERNAME="username"
LDAP_PASSWORD="secret"

# It is highly recommended, and maybe mandatory in future to use SSL/TLS connections to Active Directory,
# Set either of these to `true` to enable SSL/TLS. NOTE: this does mean you will PROBABLY need to install a 
# certificate to trust your Enterprise Root/Intermediate CA 
LDAP_USE_SSL=
LDAP_USE_TLS=
```

### Connection (LDAPS/TLS) ###

To enable LDAP+SSL or LDAP+TLS you should use _EITHER_ of these options, but not both. You probably want
`LDAP_USE_SSL` if you are using Active Directory.

```dotenv
LDAP_USE_SSL=true
# OR
LDAP_USE_TLS=true
```

Unless your LDAPS server uses a publicly trusted certificate, which is unlikely, you will need to trust the CA that
signed the LDAPS server's certificate.

When using PHP and LDAPS, there are a few different places to add your trusted CA certificates. 
There isn't really an order of precedence, but this order is from most specific to least specific location:

- LDAP options `LDAP_OPT_X_TLS_CACERTFILE` and `LDAP_OPT_X_TLS_CACERTDIR`.
- via php.ini `openssl.cafile`.
- (If openssl.cafile doesnt exist) via php.ini `openssl.capath`. This option expects
  PEM (Base64) encoded certificate files that have been processed with `c_rehash`.
- system trust store. Different for every operating system so you will have to look this up.



### Laravel ###

Further configuration of Laravel's behaviour with LDAP authentication can be configured in the file 
[config/ldap_auth.php](../../config/ldap_auth.php). 

Here, you can configure some extra filter scopes to disallow certain kinds of users.

Most of the other variables do not need attention unless you are using another kind of LDAP service such as a 
SaaS hosted LDAP product, OpenLDAP, FreeIPA etc.

### Troubleshooting ###



## See Also ##

[Official Documentation](https://adldap2.github.io/Adldap2-Laravel/#/?id=quick-start)


