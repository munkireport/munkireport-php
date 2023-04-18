# Local Authentication

Starting with MunkiReport PHP v6, local authentication has completely changed.

* User details are now stored in the database, including the role assignment of the user.
* Users login using their e-mail address.
* The system includes an _(optional)_ "forgot password" mechanism.
* The system includes an _(optional)_ user sign-up mechanism.
* The local user **.yaml** style declaration is no longer supported.
* Specifying users in **.env** or **config.php** is no longer supported.
* You cannot create a user using the `/auth/create_local_user` API.
* You cannot generate a `user.yaml` using the `/index.php?/auth/generate` URL.
* You cannot have the `NOAUTH` login method (No Authentication) due to a system limitation.

Some of these restrictions may be removed or worked around in future.

## How do I?

### Enable Local (Database) Authentication

* You can set the environment variable `AUTH_METHODS` to include the value `LOCAL`.
* You can provide a **.env** file at the root of this project with the equivalent value.

### Log in as the admin account

If you installed MunkiReport PHP v6 from scratch or you are migrating from v5, you can use the local command
`php please db:seed --class=LocalAdminSeeder` to generate a random password reset link for the local administrator account.

Follow the link to reset the admin account (the default e-mail address of this account is `admin@localhost`).

### Rescue admin credentials

You cannot currently rescue/recover admin creds.
This is a work in progress. See [Issue #1492](https://github.com/munkireport/munkireport-php/issues/1492)

### Create more users

You can create additional users using the `php please user:create` command as shown in these examples

```shell 
$ php please user:create

 Name?:
 > Another User

 Email address?:
 > another@localhost

 Password?:
 >

User saved
```

The created user will default to the **user** role (the lowest level of privilege).

### Update a users role

You cannot currently update the role of a user except by issuing `UPDATE` statements to the database.
This is a work in progress.

### Update user details (name, locale etc)

You cannot currently update the details of a user except by issuing `UPDATE` statements to the database.
This is a work in progress.


### Configure password recovery

(TODO) We will provide info here, however you can also refer to [Laravel - Mail Configuration](https://laravel.com/docs/9.x/mail#configuration)
for a generic guide to setting up e-mail delivery.

### Enable user self-registration

We will not be supporting this feature in the immediate future.

## Migrating from v5

TODO

* Create local users from **.yaml** files.
* Some users may even have php config based users?
* Roles changes

