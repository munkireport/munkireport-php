How to configure Munkireport
=====

Munkireport is configured using a config file (config.php). 

You can also configure Munkireport using the environment, or using a `.env` file placed in the root
which contains environment variables. See `.env.example` for environment settings. This is more common for
docker deployments.

**NOTE**: Configuration is loaded in this order:

1. `config_default.php`
2. `.env / ENVIRONMENT`
3. `config.php`

Which means that `config.php` overwrites everything else.

For the time being, read config_default.php. In the comments you'll find information about each setting.

## $conf['client_passphrases']

### Default value: array()

If you want to restrict access to your munkireport server, you can add passphrases to `$conf['client_passphrases']`. Munkireport will detect that the client_passphrases array is not empty and only accepts client requests that send the correct passphrase.
If you set a passphrase on the server, you need to set the passphrase on the clients as well:

### Example

On the server, add the following string to `config.php`:

```php
$conf['client_passphrases'] = array('mysecretpassphrase')
```

On each client you need to add the passphrase to `MunkiReport.plist`:

```sh
defaults write /Library/Preferences/MunkiReport Passphrase 'mysecretpassphrase'
```

Now when you run /usr/local/munki/postflight on the client, you should see that the update server can be contacted normally.

### Notes

Munkireport will **not** set the passphrase on the client through the install script, it would be too easy for someone to get the passphrase that way. So you need to roll your own method of distributing the passphrase (via munki)

### Environment Variables

- `CONNECTION_DRIVER`: Any driver that is valid for Illuminate/Database. Examples are `sqlite` and `mysql`.
- `CONNECTION_DATABASE`: For sqlite: the path to the sqlite file. Otherwise, the name of the database.
- `CONNECTION_HOST`: The hostname or IP address of the database server.
- `CONNECTION_PORT`: The port to connect to (if non standard).
- `CONNECTION_USERNAME`: The database connection username.
- `CONNECTION_PASSWORD`: The database connection password.
- `INDEX_PAGE`: The page appended to the app root, default is `index.php?`.
- `URI_PROTOCOL`: Which server variable to use for the correct request path. Defaults to `Auto`.
- `WEBHOST`: The URL to the server hosting the application, including the schema eg. `https://munkireport.local`.
- `SUBDIRECTORY`: If your application is installed underneath a subdirectory, define this, eg. `/munkireport`.
- `SITENAME`: The site name which will appear in the title bar of your browser, Default: `MunkiReport`.
- `AUTH_METHODS`: A comma separated list of supported Authentication methods. Any combination of:
    - `NOAUTH`: No authentication required
    - `LDAP`: LDAP Authentication
    - `AD`: Active Directory Authentication
