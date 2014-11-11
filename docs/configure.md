How to configure Munkireport
=====

Munkireport is configured using a config file (config.php).

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
