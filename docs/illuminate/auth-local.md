# Local (Database) Authentication #

## Creating local users

- Create a local user with the please command:
```
./please user:create
```

You will be prompted for an email address, user name, and password.

The /login screen will be asking for:
- email address
- password

If you have enable the admin menu by assigning an Admin Role to a user in the .env file:

```
ROLES_ADMIN="youradmin@example.com"
```

You will be able to add Business Units and Machine Groups.

The username and password of a user could be the same as the username and password of a Machine Group ( the email address is not used by Machine Group ).
