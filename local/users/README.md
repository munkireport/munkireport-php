# Local users

Users are defined by a file called `username.yml`
This file should contain the password hash called `password_hash`
You can generate these files by visiting `index.php?/auth/create_local_user`
Please make sure that in your .env "AUTH_METHODS" contains "LOCAL", otherwise it will not work.

## Example:

`geronimo.yml`

contains

```yaml
password_hash: $P$BnYH6NRCRO1lWHK6rkjFb0s.CmDtmm0
```

After you downloaded the `.yml` file you can drop it in the `users` directory in the root of the project and you should be able to log in.

Note: You can provide alternative directory locations to store the users in by setting

```bash
AUTH_LOCAL_SEARCH_PATHS=/path/to/your/users[, /another/path]
```
