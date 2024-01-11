# Docker Setup (v6 beta) #

The container image is available from ghcr (GitHub container registry) via:

        docker pull ghcr.io/munkireport/munkireport-php:wip

If you use docker-compose, you can have a look at
[/docker-compose.wip.yml](/docker-compose.wip.yml) for an example configuration.

## Requirements ##

* (Optional) a MariaDB or MySQL database is strongly recommended unless you are using this as a test/sandbox environment.
* You must generate an `APP_KEY` env var. If you start the container without one, it will print one that you can use and then quit.
  The `base64:xyz...` prefix must be included.
* You must now provide a value for `APP_URL` which contains the URL that people will access, eg:
  `APP_URL=https://munkireport.something.xyz`. This is used to generate links.

## First Login ##

* A default user, called `admin@localhost` will be created if you have never created any users.
* A password reset link is provided in the logs which you can visit to reset the password for `admin@localhost`.
* If you forget the password or you could not visit the reset page, you can also enter a new password by running this command in the container:

    /var/munkireport/please user:reset-password "admin@localhost"


## Configuration ##

Most MunkiReport-PHP configuration is exposed as environment variables, with a few exceptions when
doing some advanced configuration.

The default listening port for the MunkiReport-PHP container is port `8080`, this is because the
container will run as an unprivileged user.

### Environment Variables ###

All of the environment variables specified in your `.env` file are available to be passed in to your
container runtime. A short list of the most common options is below:

| Variable             | Default               | Description      |
|:---------------------|:----------------------|------------------|
| APP_URL              | http://localhost:8080 | This URL will be used to construct links, and should be the one people use to access MunkiReport |
| APP_KEY              | <generated>           | The app key is used for encryption. If you do not specify one it will be generated. You must configure this |
| DB_CONNECTION        | sqlite                | The connection type, one of: (mysql, sqlite) others not supported (yet) |
| DB_HOST              | 127.0.0.1             | The database host to connect to |
| DB_PORT              | 3306                  | The database port to connect to  |
| DB_DATABASE          | munkireport           | The database name to connect to. In the case of SQLite this can be the absolute path to the database file  |
| DB_USERNAME          | root                  | The user to authenticate to the database with |
| DB_PASSWORD          | <blank>               | The password to authenticate to the database with |
| MODULES              | munkireport,managedinstalls,disk_report | The modules to enable (This doesn't mean that theyre installed already) |
| AUTH_METHODS         | LOCAL                 | The authentication method(s) available: any of: (LOCAL, SAML, NOAUTH, AD) |


#### Advanced Options ####

| Variable             | Default               | Description      |
|:---------------------|:----------------------|------------------|
| CONNECTION_CHARSET   | utf8mb4               | |
| CONNECTION_COLLATION | utf8mb4_unicode_ci    | |
| CONNECTION_ENGINE    |                       | |
| CONNECTION_SSL_KEY   |                       | (mysql only) TLS client key |
| CONNECTION_SSL_CERT  |                       | (mysql only) TLS client certificate |
| CONNECTION_SSL_CA    |                       | (mysql only) TLS certificate authority |
| APP_NAME             | MunkiReport           | Was: SITENAME, Used to customise the title of the app |



#### Deprecated in v6 ####

| Variable             | Default               | Description      |
|:---------------------|:----------------------|------------------|
| INDEX_PAGE           | index.php?            | v6 uses mod_rewrite or similar, so the `index.php?` url is only available for backwards compatibility |

### Volumes ###

You may replace these with docker volumes or persistent volumes:

* `/var/munkireport/storage` Laravel storage items such as logs, caches, rendered views, etc.
* `/var/munkireport/app/db` Legacy path for sqlite3 database only.
