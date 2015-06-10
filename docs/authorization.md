# Authorization, Roles and Groups

MunkiReport uses Role Based authorization model, which means that users can do things based on the role they have. Any user can have only one role.
At the moment there are 4 roles defined:

* Admin 
* Manager
* User
* Nobody

## No Business Units

When Business Units are **not** configured, the following authorizations apply:

Role    | View         | Delete Machine 
------- | ------------ | -------------- 
Admin   | All machines | Yes            
Manager | All machines | Yes       
User    | All machines | No             


## Business Units

When Business Units are enabled, the roles change a little bit.
A user that does not have an admin role and is not found an a business unit gets the role of **nobody**.

Role    | View         | Delete Machine | Edit Business Units
------- | ------------ | -------------- | -------------------
Admin   | All machines | Yes            | Yes
Manager | BU only      | BU only        | No
User    | BU only      | No             | No
Nobody  | No machines  | No             | No


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

To reference this group in the roles array, prefix the name with @. You can also use this group in Business Units. At the moment, it is not possible to nest groups

## View session variables

If you want to see the actual authorization settings, and the reason a user got a certain role, you can view the current settings here:

```
http://example.com/index.php?/auth/set_session_props/1
```

## Authorizations

and there are two authorizations enabled:

* global - view everything
* delete_machine - be able to delete a machine from the database

By default, users with the **admin** role have the 'global' and the 'delete_machine' authorization. users with the **manager** role only have the 'delete_machine' authorization.
You can override the authorizations in config.php, but don't do that unless you know what you are doing!
