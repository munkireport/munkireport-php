# MunkiReport to Laravel Auth #

This is such a huge change, it requires its own document.

MunkiReport authentication and authorization is so different to Laravel's model, we have to dig up
the entire thing and try to simulate the older system as much as possible. This document is designed to 
document those design decisions.

## User Model ##

It would be unlikely that the existing Eloquent User Model would fit into MunkiReport, so we should adapt
a User model back to the original schema.

## Session Data ##

This section attempts to capture the session data that must be replicated into the Laravel system in order to
provide a backwards-compatible look at authorization.

Starting off with [app/lib/munkireport/AuthHandler.php](../../app/lib/munkireport/AuthHandler.php)

`setSessionProps($show = false)` contains the meat of the session data that future authorization decisions
are based on.

```php
if (in_array('*', $members)) {
    $_SESSION['role'] = $role;
    $_SESSION['role_why'] = 'Matched on wildcard (*) in '.$role;
    break;
}

...

if (in_array(substr($member, 1), $_SESSION['groups'])) {
    $_SESSION['role'] = $role;
    $_SESSION['role_why'] = 'member of ' . $member;

    break 2;
}

...etc
```

Role membership in MunkiReport comes down to the value of `conf('roles')`. Laravel doesn't have a concept of
roles per-se but there might be Policy objects that enforce those rules. Policies are flexible enough to grapple
with role based or group based access.

The other major part of the session data is the machine groups.

The application keeps a session variable of all machine group IDs which are allowed based upon whether the
user has a specific business unit membership or not.

This is something that can be overcome with the Policy object in Laravel.

## Compatibility of Configuration ##

There is almost no cross-compatibility of auth configuration from MunkiReport to Laravel, which means
there has to be a reasonable upgrade path.

## Authorization ##

### v5 and earlier ###

The old mode of authorization is covered on the wiki [here](https://github.com/munkireport/munkireport-php/wiki/Authorization%2C-Roles-and-Groups).

The basic architecture is as follows:

* Each user can have one role (`admin`, `manager`, `archiver`, `user`).
* Each role is authorized to perform a list of actions, defined in config array `authorizations`. The actions are 
  *archive*, *delete_machine*, and *global* (which equals to global admin).
* Enabling business unit functionality means the definition of `manager`, `archiver`, and `user` are scoped to a business unit (and all the machine groups in the BU). 
  I.E: If you attempt to archive or delete a device that does not belong to your business unit (via a machine group), you cannot perform that action unless you
  hold the global admin role *OR* you are added to the business unit.

The following API's are used in v5 to calculate your authorization to perform actions:

* The function `authorized()` which appears in `site_helper.php`, checks whether an action (like `delete_machine`) can be performed by the current user.
  It is attached to every controller that uses the KISSMVC framework so that they may call `$this->authorized($what)` in the controller action. If no action is specified
  it is used as a simple *if user logged in* check. It is also used for restricting *admin* functions. It is never used for Business Unit restrictions.
* If business units are enabled, the machine groups that you can access are unpacked during login (`AuthHandler.php::setSessionProps()`) then added to `$_SESSION['machine_groups']`.
* When generating a list of machines that belong only to business units you have access to, `get_machine_group_filter()` is used to filter machines that should not be visible to you.


### v6 ###

The Laravel Framework introduces two main mechanisms for authorization, [Gates](https://laravel.com/docs/10.x/authorization#gates) 
and [Policies](https://laravel.com/docs/10.x/authorization#creating-policies).

**Gates** are used for simple yes/no feature based authorization. This is used for things like disabling access to an administrative area.
In the case of MunkiReport v5 this is basically the "global" admin authorization.

**Policies** are more nuanced and based on accessing ORM records based on their attributes.
These are required to apply Business Unit functionality and grant access to view, archive, and delete machines based on your business unit
membership.

MunkiReport makes the application of Policies a little fuzzy because our machines are not single models, but composed
of multiple models. For the purposes of making everything clear we should consider ReportData + Machine models to be authoritative for the
rest of the inventory data. I.E if you can delete a machine, you can delete all the associated models.

Policy actions for Machine/ReportData are as follows:

* **view** The user can view the data and the data appears in a list - This is required to hide data when business units is enabled, otherwise it always returns true.
* **archive** The user can archive a machine - Must be archiver or admin in the global scope or in the business unit.
* **delete** The user can delete a machine - Must be admin or manager in the business unit.



