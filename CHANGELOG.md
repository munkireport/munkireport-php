### 2.0.11 (July 6, 2014)

This release has some changes to the way preflight scripts are handled: 

* scripts with a non-zero exit status will **not** terminate a munki run. If you rely on this behaviour, you should move your script to <code>preflight_abort.d</code>
* all scripts (including preflight) are aborted after 10 seconds. Munkireport will emit a warning when a timeout is reached.

FIXES

* Better widget sorting (thanks to @nbalonso)
* jQuery 2.1.0
* Bootstrap 3.1.1
* Date display fixed in install history and client detail
* AD module (thanks to @nbalonso)
* MySQL create table now uses default innodb and utf-8
* FV users in security listing (thanks to @diwanicki)
* Preflight scripts handling improved

NEW FEATURES

* ARD info fields
* CPU info (thanks to @joshua-d-miller)
* Add your custom css and js files to MR
* External (and internal) displays tracking (thanks to @nbalonso)
* Display vendor strings added (thanks to @diwanicki)
* Bluetooth device tracking (thanks to @gmarnin)
* Site name on login page (thanks to @gmarnin)
* Client uptime reporting
* Added postflight.d and preflight_abort.d
* verbosity is now controlled by munki (with munki 1.0.0.1883 or 2.0.0.2086 and up)

### 2.0.10 (June 30, 2014) - PRE-RELEASE

This release has some changes to the way preflight scripts are handled: 

* scripts with a non-zero exit status will **not** terminate a munki run. If you rely on this behaviour, you should move your script to <code>preflight_abort.d</code>
* all scripts (including preflight) are aborted after 10 seconds. Munkireport will emit a warning when a timeout is reached.

FIXES

* Better widget sorting (thanks to @nbalonso)
* jQuery 2.1.0
* Bootstrap 3.1.1
* Date display fixed in install history and client detail
* AD module (thanks to @nbalonso)
* MySQL create table now uses default innodb and utf-8
* FV users in security listing (thanks to @diwanicki)
* Preflight scripts handling improved

NEW FEATURES

* ARD info fields
* CPU info (thanks to @joshua-d-miller)
* Add your custom css and js files to MR
* External (and internal) displays tracking (thanks to @nbalonso)
* Display vendor strings added (thanks to @diwanicki)
* Bluetooth device tracking (thanks to @gmarnin)
* Site name on login page (thanks to @gmarnin)
* Client uptime reporting
* Added postflight.d and preflight_abort.d
* verbosity is now controlled by munki (with munki 1.0.0.1883 or 2.0.0.2086 and up)

### 2.0.9 (June 30, 2014) - PULLED

This release is pulled because of some errors that prevent munki from running. The errors occur on some 10.6 clients that have display_info reporting enabled. See #117 an #125

### 2.0.8 (March 5, 2014)

FIXES

* Speed improvement for MySQL users through indexes
* Speed improvement for SQLite by dropping permissions check
* Better session handling
* Improved error handling/reporting for db queries
* updated Font Awesome to 4.0.3, moment.js to 2.5.1
* fixed AD Information report (thanks to @nbalonso)
* fixed automated warranty check
* Spanish translation update (thanks to @nbalonso)

### 2.0.7 (Februari 11, 2014)

FIXES

* Updated disk_info for 10.5 clients
* Updated to Bootstrap 3.1.0
* Improved error handling
* Compatible with MySQL sql_mode = TRADITIONAL 

FEATURES

* Automated warranty check
* Filevault Escrow (thanks to @gmarnin)
* SSH link in client view (thanks to @timsutton)
* Added LDAP Bind info (thanks to @rsaeks)

### 2.0.6 (Januari 14, 2014)

This is a small bug fix release that fixes #66.

### 2.0.5 (Januari 13, 2014)

FIXES

* Updated to Bootstrap 3.0.2
* Fixed printing
* Fixed disk_info reporting
* Fixed munkireport counters when munki server unreachable
* Fixed purchase date calculation

FEATURES

* Moved to modular reporting
* Added german localization (thanks to @fridomac)
* Added proxy support for Apple Warranty Lookup
* Added LDAP authentication
* Added AD authentication (thanks to @nbalonso)
* Extended AD bind reporting (thanks to @nbalonso)
* Added support for https redirecting (thanks to @nbalonso)

MISC

* Documentation updates
* Some UI changes

### 2.0.4 (December 2, 2013)

FIXES

* Added some support for 10.5 clients
* Fixed some casing issues for Linux servers
* Fixed GB column in machine report

FEATURES

* Added client authentication
* Added directory services report (thanks to @gmarnin and @nbalonso)
* Added ability to disable modules
* Improved listing views
* Improved search in listing views
* Added munki version widget (thanks to @nbalonso)
* Added memory widget (thanks to @nbalonso)

MISC

* Documentation updates
* Small UI changes
* Database migration support

### 2.0.3 (November 10, 2013)

FIXES

* Fixed disk size issue on MySQL
* Preflight does not stop munki when report server is down
* Moved to bootstrap 3.0.1

FEATURES

* Added network module
* Length menu now includes all
* No_auth authentication method
* Pending munki and apple updates widgets (thanks to nbalonso)

MISC

* Documentation updates
* Small UI changes

### 2.0.2 (October 30, 2013)

FIXES

* Fixed case issues for Linux hosts
* Fixed filevault status reporting module for 10.9
* web.config now has better protection for database (on IIS servers)
* Fixed filevault, diskreport and localadmin install scripts

FEATURES

* Added local admin module

### 2.0.1 (October 23, 2013)

FIXES

* Fixed installer script
* FIxed installer plist
* Improved multiple table lookups

FEATURES

* Delete clients from list view
* Search field clear button
* Responsive tables
* Security listing (with filevault status)

### 2.0.0.336 (October 19, 2013)

* Initial release of Munkireport-php v2
