# Laravel Jetstream Usage in MunkiReport PHP #


## Features ##

Some features of Jetstream are not enabled or not yet in use:

* (Disabled) Terms and Conditions / Privacy Policy
* (Disabled) Profile Photos
* (Disabled) Teams Support, because we support this via Business Units.
* (Disabled) Local Account Deletion, because v5 does not support this.

These features are new in MunkiReport v6:

* API Token Management
* Browser Session Listing

## Laravel Fortify Features ##

Jetstream includes Fortify for Authentication and Authorization, the following features are disabled:

* (Disabled) User sign-up/Registration
* (Disabled) E-mail based validation
* (Disabled) Multi-Factor Authentication (Just use your IdP if you require MFA)

These features are enabled for MunkiReport v6:

* User based self-password reset
* Update user profile
* Update user password
* Rate-limiting failed logins

