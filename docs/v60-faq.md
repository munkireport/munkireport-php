# V6 Frequently Asked Questions #

## I can't login using my normal local user(s) ##

In version 6 of MunkiReport PHP, the file-based .yaml authentication has been replaced by database authentication to
support a ton of new integrations.

In the short term, we have provided some CLI commands which you can use to create new users or reset the admin password.

If you don't have access to the console or you can't execute commands in your container, you must go through the 
password reset process in the UI.

## I am receiving an error about the facade root ##

Example:

```shell
Fatal error: Uncaught RuntimeException: A facade root has not been set. in /munkireport-php/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php:258
```

This can happen if you have migrated to version 6 and did not supply an `APP_URL` environment variable (or via .env file). 
The environment variable indicates how people will access your installation and is required as of Version 6.0.

Side note: It is required because a container can't really know what the public facing URL of its own website will be
when fronted by a load balancer or similar.

## Failed to open stream: Permission denied When trying to generate an `APP_KEY` encryption key ##

A container filesystem can sometimes be read-only for security reasons except for some reserved paths.

In this case, you cannot supply all of your configuration through the `.env` file, you must configure it via your
container environment variables.

To generate a new encryption key without modifying the `.env` file, you can use the command:

```shell 
$ php please key:generate --show
```

## Business Units are not restricted from performing actions on other Business Unit machines ##

This is a TODO in v6.0 (wip branch)

