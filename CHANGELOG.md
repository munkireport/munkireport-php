### [3.0.0-beta.1](https://github.com/munkireport/munkireport-php/compare/v2.16.0...HEAD) (Unreleased)

The third major version of munkireport attempts to solve a couple of longstanding issues concerning security, dependency management and database management. Most notable changes are:

- Minimum PHP version is 7.0
- Document root is now in a directory called `/public`. The best practice is to serve your munkireport instance from this directory or make a symlink to this directory.
- If you install munkireport via the commandline, you need to install all dependencies first via [composer](https://getcomposer.org)
- There is a new database configuration format `$conf['connection']`. See `config_default.php` on how to use it.
- Database migrations (also the initial ones) are not running automatically. You need to run migrations via the commandline tool `php database/migrate.php`

See also the [Upgrade v3 guide](https://github.com/munkireport/munkireport-php/wiki/How-to-Upgrade-to-v3) and [Quick demo v3](https://github.com/munkireport/munkireport-php/wiki/Quick-demo-V3)

FIXES
- NVMe Support in SMART Stats module (#895) @tuxudo
- 

### [2.15.2](https://github.com/munkireport/munkireport-php/compare/v2.14.3...v2.15.2) (Sept 28, 2017)

FIXES
- Fixed Apple icon url
- Removed `default` method from Schema.php which caused errors in PHP < 7.0.0
- Fix for #812
- Fix for #813
- en.json and fr.json - @lolipale
- Improved error handling around the firmwarepasswd binary @rickheil
- Case Sensitivity for PlistBuddy - @GordSpence

NEW FEATURES
- Add Munki 3 ManagedInstalls Keys
- Kernel Extensions Module (KEXT) - @tuxudo
- More default filters for inventory module - @poundbangbash
- APFS support in disk_report module
- Invisible CAPTCHA (you need to generate new keys if you were using the previous version)
- Schema class to help with migrations

### [2.14.3](https://github.com/munkireport/munkireport-php/compare/v2.13.2...v2.14.3) (May 19, 2017)

FIXES
- Blank client detail page on php < v5.5
- Errors on submit in smart_stats module
- Sorting in Machinegroups - @cwhits
- event module enabled by default
- uninstall script for mbbr_status (#790) - @gmarnin  
- app usage widget duplicates (#789)- @tuxudo
- missing caching graph and app usage widgets (#788) - @tuxudo
- gsx migration
- file permissions
- Client detail button scaling
- Misc small fixes
- Power module adjustments - @tuxudo
- Certificate parsing - @kevinmcox
- Unique error reporting  - @poundbangbash
- Updated GSX module - @tuxudo
- Firewall widget fix
- Changed soon expiration for certs to 1 month - @poundbangbash
- Drive health status - @poundbangbash
- Lets Encrypt cert check - @poundbangbash
- Fixed theme (and added one extra) - @WardsParadox

NEW FEATURES
- List links on widgets - @poundbangbash
- French translation - @lolipale
- Uninstall status messages in events
- Firewall state reporting - @rickheil
- Malware Bytes Breach Remediation module - @cleavenworth
- User sessions module - @tuxudo
- Uptime widget and munki widget UI

### [2.13.2](https://github.com/munkireport/munkireport-php/compare/v2.12.0...v2.13.2) (April 7, 2017)

FIXES
- Converting 'fake' null placeholders to actual null values in smart stats module - @poundbangbash  
- Older munkireport client support
- Support for storing and retrieving null values from the db
- Converting 'fake' null placeholders to actual null values - @tuxudo
- Additional USB categories -  @poundbangbash
- ARD_AllLocalUsers - @rickheil
- PHP < 5.5 support
- Missing munkiinfo locales - @tuxudo
- Munkiprotocol widget links - @tuxudo
- Time Machine module by @tuxudo
- Misc small fixes

NEW FEATURES
- Moment.js to 2.17
- SSL options for https lookups
- Support for munki v3
- Aggregate 'registered_clients_widget' data per month
- App Usage module - made by @tuxudo
- added hardware base model widget
- localisation updates by @tuxudo
- Caching module by @tuxudo
- USB module by @MiqViq and @tuxudo
- MunkireportInfo module by @tuxudo
- Software Update module by @tuxudo
- Revamped Power module by @tuxudo
- GPU module by @tuxudo
- Dynamic lookup for managed_install_dir by @weswhet
- Fonts module by @tuxudo
- Homebrew module by @tuxudo
- Network shares module by @tuxudo
- SMART Stat Module by @tuxudo
- Fans and Temperatures Module by @tuxudo
- Firmware Escrow module by @gmarnin
- Option to hide unused modules from the WebGUI by @nathanperkins
- French localisation by @lolipale
- German localisation by @fridomac

### [2.12.0](https://github.com/munkireport/munkireport-php/compare/v2.11.0...v2.12.0) (December 6, 2016)

FIXES
- System Status page  (#560)

NEW FEATURES
- Theme switcher with 16 themes from https://bootswatch.com
- All graphs are now converted to d3
- Widget search (#581) See also: https://github.com/munkireport/munkireport-php/wiki/Custom-Widgets
- Allow for graph color customisation See also: https://github.com/munkireport/munkireport-php/wiki/Graphs

### [2.11.0](https://github.com/munkireport/munkireport-php/compare/v2.10.1...v2.11.0) (December 6, 2016)

FIXES
- Disk free space on non mounted volumes thanks to @VitosX
- Network location widget thanks to @poundbangbash
- French localisation updates thanks to @lolipale
- German localisation updates thanks to @fridomac
- Misc bugfixes

NEW FEATURES
- Clientside script now reports sizes of uploaded files
- SCCM module thanks to @computeronix
- Backup2go module thanks to @pnerum and @johannijdam

### [2.10.1](https://github.com/munkireport/munkireport-php/compare/v2.9.2...v2.10.1) (October 13, 2016)

FIXES
- Fixed a merge conflict in site_helper.php
- authldap regression introduced in 2.9
- wifi module regression introduced in 2.9 thanks to @clburlison
- French localisation updates thanks to @lolipale
- munkireport and managedinstalls scripts now run on postflight
- Certificate module (#557)
- Speed up printer table by adding indexes
- Misc bugfixes

NEW FEATURES
- New Favicon thanks to @WardsParadox

### [2.9.2](https://github.com/munkireport/munkireport-php/compare/v2.8.5...v2.9.2) (August 28, 2016)

FIXES

* Fix munkireport table for new installs
* Fix deploystudio tab in client view
* Link from inventory listing thanks to @poundbangbash
* Manager role can remove machines thanks to @poundbangbash
* Divide by zero error for MacBooks without battery thanks to @poundbangbash
* GSX and Find My Mac presentation thanks to @gmarnin
* Improved tag search (#372)
* SIP status reporting improved thanks to @clburlison
* Update for GSX module thanks to @tuxudo
* Scrollable modal window, fixes #461
* Fix several deep links, #460, #462
* French localisation updates thanks to @lolipale
* German localisation updates thanks to @fridomac
* Event widget only shows 50 events
* Increase inventorypath limit to 1024 chars, fixes #478
* Fix target volume (#492)
* Fix for Obsolete machines (#496) thanks to @tuxudo
* Removed "~VIN," from machine description (#498) thanks to @tuxudo
* Added WiFi hardware check (#499) thanks to @tuxudo
* Fix marker images for location reporting
* Disk reporting on Snow Leopard thanks to @Steffan-Ravn
* Last seen column in munki listing (#334)
* Custom unserializer (due security issues with native `unserialize()`)
* Improve Profile listing query (#491)
* Improved inventory listing
* Only show edit button when admin (#311)

NEW FEATURES

* curl configuration item, addresses #374
* FindMyMac module thanks to @clburlison
* check/uncheck all in filter
* Event reporting for certificate module
* Preference for ScriptTimeOut #472
* Support ManagedInstallDir relocation thanks to @weswhet
* Munkireport System status page - basic reporting on db status and php
* link to everymac.com in the client detail page
* Add module templating (run build/add_module.sh module_name)
* Managedinstalls module (replaces part of the munkireport module)
* DeployStudio Module thanks to @tuxudo and @n8felton
* Add munkiprotocol listing (#462)
* Add computername to page title (#444)
* GoogleMaps API key (#510)
* Support for multiple CrashPlan destinations
* Munki Web Admin 2 links thanks to @gmarnin
* ReCaptcha for login thanks to @computeronix

### [2.8.5](https://github.com/munkireport/munkireport-php/compare/v2.7.2...v2.8.5) (April 4, 2016)

FIXES since 2.8.4
* WiFi module robustness thanks to @tuxudo and @kujeger
* documentation on GSX module thanks to @tuxudo and @gmarnin
* location report

FIXES since 2.8.3
* Location module packaging thanks to @clburlison

FIXES since 2.8.2

* SIP status thanks to @clburlison
* Wi-Fi module more robust thanks to @tuxudo
* Location module packaging thanks to @clburlison

FIXES

* Markdown now accepts line breaks in client comments
* FileVault 2 reporting fix for #375 and #378
* curl options for submit.preflight thanks to @kujeger

NEW FEATURES

* Location module - thanks to @clburlison and @gmarnin
* GSX module thanks to @tuxudo
* Security module thanks to @gmarnin
* Munkiinfo module thanks to @erikng and @clburlison
* Wi-Fi module thanks to @tuxudo
* Printer widget thanks to @tuxudo
* Hotkey support
* German localisation updates thanks to @fridomac
* French localisation updates thanks to @lolipale
* stretch map to viewport thanks to @rfitzwater

### [2.7.2](https://github.com/munkireport/munkireport-php/compare/v2.6.0...v2.7.2) (December 12, 2015)

FIXES

* Fix Delete machine (which was broken in the 2.7.1 release) thanks to @gmarnin
* RESTified all widgets
* Tags input
* Warranty check removed (can't use that anymore since Apple put a captcha on the status page)
* FileVault status widget

NEW FEATURES

* Event module - collects events and shows them on the dashboard
* Crashplan module
* New client graph
* Printer module thanks to @gmarnin
* German translation thanks to @fridomac
* Support for curl headers (and authentication) thanks to @morgant

### [2.6.0](https://github.com/munkireport/munkireport-php/compare/v2.5.3...v2.6.0) (October 7, 2015)

FIXES

* TLS1.2 support - NSUrl replaces urllib2 thanks to @gneagle and @pudquick
* Better checking of preflight scripts
* Uptime fix for El Capitan
* ADLDap utils (deprecated modifier)

NEW FEATURES

* Bluetooth widget thanks to @gmarnin and @nbalonso
* Configurable disk size thresholds
* German localisation thanks to @fridomac
* Etag support for install controller (used by AutoPkg recipe)
* initial support for 'tagging' machines
* Datatables 10
* Download CSV, Print, and Copy to clipboard
* better FileVault status widget

### [2.5.3](https://github.com/munkireport/munkireport-php/compare/v2.4.2...v2.5.3) (July 31, 2015)

FIXES

* Filesize formatter now uses base 1000 instead of 1024
* Fix uninstall scripts
* Session vars are updated when adding/removing machine groups
* Better support for Corestorage volume reporting
* Reordered buttons in some widgets thanks to @poundbangbash
* Servermetrics model

NEW FEATURES

* Support for Business Units - [Read more](https://github.com/munkireport/munkireport-php/blob/master/docs/business_units.md)
* Support for Machine Groups - [Read more](https://github.com/munkireport/munkireport-php/blob/master/docs/machine_groups.md)
* Role Based Access [Read more](https://github.com/munkireport/munkireport-php/blob/master/docs/authorization.md)
* Filter on Machine Group
* Server metrics module (reports server metrics for OSX Server)
* Services module (reports on running services on OSX Server)
* Timemachine module (reports on Time Machine backups)
* Certificate module (reports on certificate expiration dates)
* Comment module (for custom views: add admin comments)
* Improved Disk reporting (now reports on all connected HFS Disks, reports on RAID and FUSION drives)
* Live updates on certain widgets
* German localisation thanks to @fridomac
* Reporting on OS Buildversion
* Disk size thresholds

### [2.4.2](https://github.com/munkireport/munkireport-php/compare/v2.3.0...v2.4.2) (April 25, 2015)

FIXES

* Disable power reporting for desktops
* Fix labels for donut chart in network locations
* Fix machine migration script
* Fix warranty lookups
* Improved installer package

NEW FEATURES

* Select modules in installer
* Profile reporting (thanks to @dingerkingh)
* Russian localisation (thanks to @little2112)
* improved power widget labels (thanks to @rickheil)

### [2.3.0](https://github.com/munkireport/munkireport-php/compare/v2.2.0...v2.3.0) (March 11, 2015)

FIXES

* TLS support for 10.6 clients
* Sortable Managed installs table (thanks to @dingerkingh)

NEW FEATURES

* App version report page (thanks to @rsaeks)
* Updated German translation (thanks to @fridomac)
* Improved network graphs
* Laptop battery reporting (thanks to @kene101, @dingerkingh and @rickheil)

### [2.2.0](https://github.com/munkireport/munkireport-php/compare/v2.1.0...v2.2.0) (Februari 8, 2015)

FIXES

* Migration script 003_machine_add_cpu.php (thanks to @choules)
* TLSv1 support (thanks to @joshua-d-miller)
* OS version is now stored as INTEGER for better sorting/comparing

NEW FEATURES

* Create installer pkg for MR-PHP (thanks to @znerol)
* NVD3 charting library added (with one included graph showing the growth of your munki flock over time)

### [2.1.0](https://github.com/munkireport/munkireport-php/compare/v2.0.11...v2.1.0) (November 10, 2014)

FIXES

* Improved error handling on 10.5
* ARD error #139
* ISO dates for display #138 and #156 (thanks to @nbalonso)
* DataTables 1.10.2
* Disk listing
* Warranty widget
* Install history date sort

NEW FEATURES

* Uptime widget (thanks to @nbalonso)
* More display vendors (thanks to @nbalonso and @diwanicki)
* Localization with i18n js library
* Updated SMART status widget (thanks to @kene101)
* Authorization for delete_machine

### [2.0.11](https://github.com/munkireport/munkireport-php/compare/2.0.10...v2.0.11) (July 6, 2014)

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

### [2.0.10](https://github.com/munkireport/munkireport-php/compare/2.0.9...2.0.10) (June 30, 2014) - PRE-RELEASE

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

### [2.0.9](https://github.com/munkireport/munkireport-php/compare/2.0.8...2.0.9) (June 30, 2014) - PULLED

This release is pulled because of some errors that prevent munki from running. The errors occur on some 10.6 clients that have display_info reporting enabled. See #117 an #125

### [2.0.8](https://github.com/munkireport/munkireport-php/compare/2.0.7...2.0.8) (March 5, 2014)

FIXES

* Speed improvement for MySQL users through indexes
* Speed improvement for SQLite by dropping permissions check
* Better session handling
* Improved error handling/reporting for db queries
* updated Font Awesome to 4.0.3, moment.js to 2.5.1
* fixed AD Information report (thanks to @nbalonso)
* fixed automated warranty check
* Spanish translation update (thanks to @nbalonso)

### [2.0.7](https://github.com/munkireport/munkireport-php/compare/2.0.6...2.0.7) (Februari 11, 2014)

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

### [2.0.6](https://github.com/munkireport/munkireport-php/compare/2.0.5...2.0.6) (Januari 14, 2014)

This is a small bug fix release that fixes #66.

### [2.0.5](https://github.com/munkireport/munkireport-php/compare/2.0.4...2.0.5) (Januari 13, 2014)

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

### [2.0.4](https://github.com/munkireport/munkireport-php/compare/v2.0.3...2.0.4) (December 2, 2013)

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

### [2.0.3](https://github.com/munkireport/munkireport-php/compare/v2.0.2.369...v2.0.3) (November 10, 2013)

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

### [2.0.2](https://github.com/munkireport/munkireport-php/compare/2.0.1.353...v2.0.2.369) (October 30, 2013)

FIXES

* Fixed case issues for Linux hosts
* Fixed filevault status reporting module for 10.9
* web.config now has better protection for database (on IIS servers)
* Fixed filevault, diskreport and localadmin install scripts

FEATURES

* Added local admin module

### [2.0.1](https://github.com/munkireport/munkireport-php/compare/2.0.0.336...2.0.1.353) (October 23, 2013)

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
