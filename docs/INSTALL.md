# Installation

## Generic Requirements

* [PHP >= 8.1](https://www.php.net/)
* [Node.JS LTS 16.x](https://nodejs.org) if any customisation of frontend components is required.
* [MariaDB >= 10.10](https://mariadb.org/) highly recommended for anything above five (5) clients.
* Any HTTP server that supports PHP or [php-fpm](https://www.php.net/manual/en/install.fpm.php) such as [NGiNX](https://nginx.org/en/)
  or [Apache HTTPD](https://httpd.apache.org/).
  * or you can optionally use any PaaS service that supports PHP as a runtime such as
    [AWS Elastic Beanstalk](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/create_deploy_PHP_eb.html),
    [Azure App Service](https://learn.microsoft.com/en-us/azure/app-service/quickstart-php?tabs=cli&pivots=platform-linux), etc.
  * or you can use a container orchestrator like Kubernetes, AWS ECS, Azure ACI etc.
* SSL/TLS Certificate (highly recommended), some modules may require this in future. You can use a self-signed cert in testing environments.


## Configuration

Configuration is *mostly* performed through the use of environment variables. You can also supply values by placing a
`.env` file in the root of the project. There may be some advanced scenarios where the configuration is just too complex
to place into an environment variable, in which case it will be documented in a file contained within [config/](../config/).

### Required Configuration

Without the following variables in the environment or as part of the `.env` file, MunkiReport won't function.
Backwards compatible names are also shown, in case you are upgrading from version 5.


| Old Name            | New Name      | Old Default      | New Default       | Description                                                                                       |
|:--------------------|:--------------|:-----------------|:------------------|:--------------------------------------------------------------------------------------------------|
| ENCRYPTION_KEY      | APP_KEY       | (empty)          | (generated)       | All encryption functions use this key incl. local authentication                                  |
| CONNECTION_DRIVER   | DB_CONNECTION | sqlite           | mysql             | The connection profile, refers to connections in [../config/database.php](../config/database.php) |
| CONNECTION_DATABASE | DB_DATABASE   | app/db/db.sqlite | munkireport       | The name of the database (or path if using SQLite).                                               |
| CONNECTION_HOST     | DB_HOST       | 127.0.0.1        | localhost         | The database hostname to connect to.                                                              |
| CONNECTION_PORT     | DB_PORT       | 3306             | 3306              | The database port.                                                                                |
| CONNECTION_USERNAME | DB_USERNAME   | root             | munkireport       | The database username.                                                                            |
| CONNECTION_PASSWORD | DB_PASSWORD   | (empty)          | munkireport       | The database password.                                                                            |
| (n/a)               | APP_URL       | (n/a)            | https://localhost | The public URL of this MunkiReport site. You **MUST** set this.                                   |

### Optional Configuration

Documentation about other parts of MunkiReport can be found in the Features guide.



## Post-Installation Tasks

### Reset the Local Administrator Password

The first time you run `please db:seed` or a container starts with a connection to a new database, the password reset URL
will be given in the output, example:

    Reset the `admin@localhost` password at https://<public url>/password/reset/abcxyz...

You may visit that URL to reset the administrator password if the administrator account was just created. If you are
upgrading from an earlier version 6 release, you will not see this message because your database already contains
an administrator user.


If you need to recover the account, you can go back and reset the password at any time using the `password:reset` command like so:

        $ php please password:reset admin@localhost

### Install optional features and modules

(TODO)
