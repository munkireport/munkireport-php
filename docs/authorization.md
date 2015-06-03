# About Authorization

MunkiReport uses Role Based authorization model, which means that users can do things based on they're role.
At the moment there are 3 roles defined:

* Admin 
* Manager
* User

and there are two authorizations enabled:

* global - view everything
* delete_machine - be able to delete a machine from the database

By default, users with the **admin** role have the 'global' and the 'delete_machine' authorization. users with the **manager** role only have the 'delete_machine' authorization.

## Add role to a user

By default all users have the admin role, due to a setting in config_default.php. To override this setting, create the following in config.php:

```php
$conf['roles']['admin'] = array('your_username');
```

This will give 'your_username' the role of admin.
You can also add groups to a role array:

```php
$conf['roles']['admin'] = array('your_username', '@admin_group');
```

This will give all users in the group 'admin_group' the role of admin. Groups can be local groups, LDAP groups or AD groups, make sure you prefix the groupname with @.

## Local groups

To make a local group, add the following to config.php:

```php
$conf['groups']['admin_group'] = array('your_username');
```

To reference this group in the roles array, prefix the name with @. You can also use this group in Business Units. It is not possible to nest groups
