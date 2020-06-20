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


