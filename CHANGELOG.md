### [5.1.5](https://github.com/munkireport/munkireport-php/compare/v5.1.4...HEAD) (Unreleased)

Mostly module updates, and a small fix for apache servers.
Security module now reports on Secure Boot and External Boot thanks to @poundbangbash Note that this change needs a database migration.

MODULE UPDATES
  - munkireport/reportdata (v2.3 => v2.4)
  - munkireport/location (v1.1 => V1.2)
  - munkireport/mdm_status (v1.7 => v1.9)
  - munkireport/security (V1.5 => v1.6)
  - munkireport/supported_os (v1.7 => V1.8)
  - munkireport/user_sessions (v1.3 => V1.4)

DEPENDENCY UPDATES
  - symfony/yaml (v3.4.36 => v3.4.37)
  - symfony/var-dumper (v4.4.2 => v4.4.4)
  - tightenco/collect (v6.11.0 => v6.13.0)
  - symfony/console (v4.4.2 => v4.4.4)
  - symfony/process (v4.4.2 => v4.4.4)
  - symfony/translation (v4.4.2 => v4.4.4)
  - nesbot/carbon (2.28.0 => 2.29.1)
  - symfony/finder (v4.4.2 => v4.4.4)


### [5.1.4](https://github.com/munkireport/munkireport-php/compare/v5.1.3...v5.1.4) (January 20, 2020)

This is a bug fix release. Most notable change: the model lookup now happens in the machine module and not in the warranty module anymore.

FIXES
- Remove deprecated get_magic_quotes_gps()
- XSS fix in debug mode
- Clean exit when server is unavailable

MODULE UPDATES
- munkireport/machine (v2.3 => v2.5)
- munkireport/warranty (v2.1 => v2.2)
- munkireport/fan_temps (v1.5 => V1.6)

DEPENDENCY UPDATES
- guzzlehttp/guzzle (6.4.1 => 6.5.2)
- symfony/process (v4.3.7 => v4.4.2)
- symfony/polyfill-php73 (v1.12.0 => v1.13.1)
- symfony/polyfill-mbstring (v1.12.0 => v1.13.1)
- symfony/console (v4.3.7 => v4.4.2)
- symfony/translation (v4.3.7 => v4.4.2)
- nesbot/carbon (2.26.0 => 2.28.0)
- illuminate/contracts (v5.8.35 => v5.8.36)
- illuminate/support (v5.8.35 => v5.8.36)
- illuminate/console (v5.8.35 => v5.8.36)
- illuminate/container (v5.8.35 => v5.8.36)
- illuminate/database (v5.8.35 => v5.8.36)
- symfony/finder (v4.3.7 => v4.4.2)
- illuminate/filesystem (v5.8.35 => v5.8.36)
- league/flysystem (1.0.57 => 1.0.63)
- symfony/polyfill-ctype (v1.12.0 => v1.13.1)
- symfony/yaml (v3.4.34 => v3.4.36)
- symfony/polyfill-php72 (v1.12.0 => v1.13.1)
- symfony/var-dumper (v4.3.7 => v4.4.2)
- tightenco/collect (v6.4.1 => v6.11.0)
- adldap2/adldap2 (v10.2.0 => v10.2.2)
- onelogin/php-saml (3.3.1 => 3.4.1)
- monolog/monolog (1.25.1 => 1.25.3)
- doctrine/cache (1.9.0 => 1.10.0)
- phpoption/phpoption (1.5.2 => 1.7.2)

### [5.1.3](https://github.com/munkireport/munkireport-php/compare/v5.1.2...v5.1.3) (November 12, 2019)

FEATURES
  - Added an upgrade script (@lcsees)
  - Added a docker compose example (@b-reich)

FIXES
  - MySQL port is now correctly used in admin panel
  - Server timeout is now 60 seconds for slow servers
  - Filter scripts for @ sign (Synology issue)
  - LaunchDaemon changed to use KeepAlive instead of WatchPaths - should prevent the runner from starting twice
  
MODULE UPDATES
  - munkireport/crashplan (v1.4 => V1.5)
  - munkireport/managedinstalls (v2.2 => V2.3)

DEPENDENCY UPDATES  
  - guzzlehttp/guzzle (6.3.3 => 6.4.1)
  - doctrine/event-manager (v1.0.0 => 1.1.0)
  - doctrine/cache (v1.8.0 => 1.9.0)
  - doctrine/dbal (v2.9.2 => v2.10.0)
  - symfony/yaml (v3.4.32 => v3.4.33)
  - symfony/var-dumper (v4.3.5 => v4.3.6)
  - tightenco/collect (v6.3.0 => v6.4.1)
  - psr/log (1.1.0 => 1.1.2)
  - adldap2/adldap2 (v10.1.1 => v10.2.0)
  - robrichards/xmlseclibs (3.0.3 => 3.0.4)
  - onelogin/php-saml (3.3.0 => 3.3.1)
  - symfony/service-contracts (v1.1.7 => v1.1.8)
  - symfony/console (v4.3.5 => v4.3.6)
  - symfony/process (v4.3.5 => v4.3.6)
  - doctrine/inflector (v1.3.0 => 1.3.1)
  - symfony/translation (v4.3.5 => v4.3.6)
  - nesbot/carbon (2.25.2 => 2.26.0)
  - symfony/finder (v4.3.5 => v4.3.6)
  - phpoption/phpoption (1.5.0 => 1.5.2)

### [5.1.2](https://github.com/munkireport/munkireport-php/compare/v5.1.1...v5.1.2) (October 19, 2019)

Increase default script timeout to 30 seconds

 MODULE UPDATES
 - munkireport/security (v1.4 => V1.5)
 
 DEPENDENCY UPDATES
 - nesbot/carbon (2.25.1 => 2.25.2)
 - league/flysystem (1.0.55 => 1.0.57)
 - tightenco/collect (v6.2.0 => v6.3.0

### [5.1.1](https://github.com/munkireport/munkireport-php/compare/v5.1.0...v5.1.1) (October 09, 2019)

Notable changes: XSS patch, Catalina support for Storage report (@rickheil)
filevault status rewrite (@tuxudo), mdm_status detail widget (@poundbangbash)

FIXES
- Prevent Cross Site Scripting attack (XSS) on login form

MODULE UPDATES
  - munkireport/disk_report (v2.4 => v2.7)
  - munkireport/filevault_status (v1.1 => V1.2)
  - munkireport/localadmin (v2.1 => v2.2)
  - munkireport/mdm_status (v1.6 => v1.7)
  - munkireport/network (v2.3 => v2.4)

  DEPENDENCY UPDATES
  - symfony/yaml (v3.4.31 => v3.4.32)
  - symfony/var-dumper (v4.3.4 => v4.3.5)
  - tightenco/collect (v6.0.3 => v6.2.0)
  - symfony/service-contracts (v1.1.6 => v1.1.7)
  - symfony/console (v4.3.4 => v4.3.5)
  - symfony/process (v4.3.4 => v4.3.5)
  - symfony/translation-contracts (v1.1.6 => v1.1.7)
  - symfony/translation (v4.3.4 => v4.3.5)
  - nesbot/carbon (2.24.0 => 2.25.1)
  - symfony/finder (v4.3.4 => v4.3.5)


### [5.1.0](https://github.com/munkireport/munkireport-php/compare/v5.0.1...v5.1.0) (September 24, 2019)

Some bugfixes and a change on how the client summary tab is rendered. Removed legacy modules.

NEW FEATURES
- Customizable client summary tab https://github.com/munkireport/munkireport-php/wiki/Client-Summary-Tab

FIXES
- Fix issue with extra slashes in URL. Some web servers can't handle the double slash and fail. (#1275) @poundbangbash
- Fix munkireport-runner: Add long_username and uid

MODULE UPDATES
  - Removed munkireport/service (v1.1)
  - Removed munkireport/servermetrics (v1.1)
  - munkireport/ard (v2.0 => 2.1)         
  - munkireport/machine (v2.2 => v2.3)         
  - munkireport/disk_report (v2.3 => v2.4)         
  - munkireport/warranty (v2.0 => v2.1)         
  - munkireport/appusage (v2.1 => v2.2)
  - munkireport/bluetooth (v1.1 => v1.2)         
  - munkireport/caching (v1.2 => v1.3)
  - munkireport/comment (v2.0 => v2.1)         
  - munkireport/crashplan (v1.2 => v1.4)         
  - munkireport/detectx (v2.1 => v2.2)         
  - munkireport/fan_temps (V1.4 => v1.5)         
  - munkireport/localadmin (v2.0 => v2.1)         
  - munkireport/network (v2.2 => v2.3)         
  - munkireport/security (v1.3 => v1.4)         
  - munkireport/timemachine (v1.3 => v1.4)         
  - munkireport/user_sessions (v1.2 => v1.3)

DEPENDENCY UPDATES
  - adldap2/adldap2 (v10.1.0 => v10.1.1)         



### [5.0.1](https://github.com/munkireport/munkireport-php/compare/v5.0.0...v5.0.1) (September 19, 2019)

Small bugfix release which incorporates updated modules.

MODULE_UPDATES
  - munkireport/disk_report (v2.1 => v2.3)
  - munkireport/certificate (v1.1 => v1.2)


### [5.0.0](https://github.com/munkireport/munkireport-php/compare/v4.3.3...v5.0.0) (September 18, 2019)

This release changes the way MunkiReport runs on the client. MunkiReport now has it's own launchDaemon, which means that it will no longer take up time when munki runs it's pre- and postflight scripts.
The MunkiReport scripts have moved to `/usr/local/munkireport`
MunkiReport log files are now found in `/Library/MunkiReport/Logs/`

To take advantage of the new scripts, create a new client package and distribute to your munki clients.

IMPORTANT
MunkiReport does not run scripts in `/usr/local/munki/preflight.d`, `/usr/local/munki/postlight.d` and `/usr/local/munki/preflight_abort.d` anymore! If you depended on these locations, please consider creating your own implementation for these. Also note that MunkiReport will not clean up the legacy directories.

MODULE UPDATES
- munkireport/profile (v1.2 => v2.0)         
- munkireport/machine (v2.1 => v2.2)         
- munkireport/munkireport (3.0 => 3.1)         

DEPENDENCY UPDATES
- symfony/process (v4.3.4)         
- symfony/service-contracts (v1.1.6)         
- symfony/polyfill-php73 (v1.12.0)         
- symfony/polyfill-mbstring (v1.11.0 => v1.12.0)
- symfony/console (v3.4.29 => v4.3.4)         
- symfony/translation-contracts (v1.1.6)
- symfony/translation (v3.4.29 => v4.3.4)
- nesbot/carbon (1.39.0 => 2.24.0)         
- illuminate/contracts (v5.5.44 => v5.8.35)
- doctrine/inflector (v1.2.0 => v1.3.0)         
- illuminate/support (v5.5.44 => v5.8.35)
- illuminate/console (v5.5.44 => v5.8.35)
- illuminate/container (v5.5.44 => v5.8.35)
- illuminate/database (v5.5.44 => v5.8.35)
- symfony/finder (v3.4.29 => v4.3.4)         
- illuminate/filesystem (v5.5.44 => v5.8.35)
- league/flysystem (1.0.53 => 1.0.55)         
- doctrine/event-manager (v1.0.0)         
- doctrine/cache (v1.6.2 => v1.8.0)         
- doctrine/dbal (v2.5.13 => v2.9.2)         
- symfony/polyfill-ctype (v1.11.0 => v1.12.0)
- vlucas/phpdotenv (v3.4.0 => v3.6.0)         
- symfony/yaml (v3.4.29 => v3.4.31)         
- symfony/polyfill-php72 (v1.12.0)         
- symfony/var-dumper (v4.3.4)         
- tightenco/collect (v6.0.3)         
- adldap2/adldap2 (v10.0.11 => v10.1.0)         
- onelogin/php-saml (3.2.1 => 3.3.0)         
- monolog/monolog (1.24.0 => 1.25.1)

### [4.3.4](https://github.com/munkireport/munkireport-php/compare/v4.3.3...v4.3.4) (July 27, 2019)

A small fix concerning business units.

FIXED
- Detecting correct machine_groups for non-admin users

### [4.3.3](https://github.com/munkireport/munkireport-php/compare/v4.3.2...v4.3.3) (July 25, 2019)

Still dealing with the fallout from the 4.3.0RC2 release :-(

FIXED
- Deleting of events

MODULE UPDATES
- munkireport/munkireport (v2.2 => 3.0)
- munkireport/network (v2.1 => v2.2)


### [4.3.2](https://github.com/munkireport/munkireport-php/compare/v4.3.1...v4.3.2) (July 18, 2019)

Fix for reportdata and managedinstalls not logging events

MODULE UPDATES
  - munkireport/reportdata (v2.2 => v2.3)
  - munkireport/managedinstalls (v2.1 => v2.2)


### [4.3.1](https://github.com/munkireport/munkireport-php/compare/v4.3.1...v4.3.1) (July 18, 2019)

Fix for event module not showing correct data

MODULE UPDATES
  - event (v3.0 => v3.1)


### [4.3.0RC2](https://github.com/munkireport/munkireport-php/compare/v4.2.2...v4.3.0RC2) (July 17, 2019)

This update changes the way some of the database queries are handled. Speed improvements for installhistory and inventory by chunking inserts.

MODULE UPDATES
  - event (v2.1 => v3.0)
  - warranty (v1.2 => v2.0)
  - comment (v1.1 => v2.0)
  - gsx (v1.2 => v2.0)
  - installhistory (v1.1 => v2.0)
  - inventory (v2.2 => v3.0)
  - reportdata (v1.5 => v2.2)
  - machine (v1.3 => v2.1)
  - detectx (v2.0 => v2.1)
  - managedinstalls (v1.2 => v2.1)
  - network (v1.4 => v2.1)
  - tag (v1.1 => v2.1)


  DEPENDENCY UPDATES
  - onelogin/php-saml (3.1.1 => 3.2.1)


### [4.2.2](https://github.com/munkireport/munkireport-php/compare/v4.2.1...v4.2.2) (July 06, 2019)

FIXES
- munkireport module (again)

### [4.2.1](https://github.com/munkireport/munkireport-php/compare/v4.2.0...v4.2.1) (July 06, 2019)

FIXES
- munkireport module

### [4.2.0](https://github.com/munkireport/munkireport-php/compare/v4.1.3...v4.2.0) (July 05, 2019)

This release contains a couple of internal improvements to make developer life easier.

CHANGES
- Developers can now use `module_processor.php` to process the client data. See for example https://github.com/munkireport/munkireport/blob/master/munkireport_processor.php
- Support for Eloquent Models. See for example https://github.com/munkireport/munkireport/blob/master/munkireport_model.php For more info see https://laravel.com/docs/5.8/eloquent
- Support for new listing format See https://github.com/munkireport/munkireport-php/wiki/Module-listings

MODULE UPDATES
- munkireport/ard (v1.1 => v2.0)
- munkireport/munkireport (v1.4 => v2.0)
- munkireport/detectx (v1.6 => v2.0)
- munkireport/displays_info (v1.3.3 => v2.0)
- munkireport/localadmin (v1.2 => v2.0)
- munkireport/machine (v1.2 => v1.3)
- munkireport/event (v1.2 => v2.1)
- munkireport/disk_report (v1.3 => v2.1)
- munkireport/appusage (v1.1 => v2.1)
- munkireport/inventory (v1.3 => v2.2)

DEPENDENCY UPDATES
- symfony/yaml (v3.4.28 => v3.4.29)
- symfony/translation (v3.4.28 => v3.4.29)
- nesbot/carbon (1.38.4 => 1.39.0)
- ralouphie/getallheaders (2.0.5 => 3.0.3)
- guzzlehttp/psr7 (1.5.2 => 1.6.1)
- symfony/debug (v3.4.28 => v3.4.29)
- symfony/console (v3.4.28 => v3.4.29)
- symfony/finder (v3.4.28 => v3.4.29)


### [4.1.3](https://github.com/munkireport/munkireport-php/compare/v4.1.2...v4.1.3) (June 22, 2019)

Small update with some improvement in the modules. The event module now allows for filtering events in the messages pane (see: https://github.com/munkireport/event/blob/master/README.md#configuration-file) Thanks to MDOYVR 2019 Hack Night! MatX: you rock!

supported_os gained reporting on macOS 10.15 aka Catalina.

MODULE UPDATES
- reportdata (v1.4 => v1.5)
- event (v1.1 => v1.2)
- detectx (v1.5 => v1.6)
- localadmin (v1.1 => v1.2)
- softwareupdate (v1.2 => v1.2.1)
- supported_os (v1.5 => v1.7)

DEPENDENCY UPDATES
- league/flysystem (1.0.51 => 1.0.53)
- vlucas/phpdotenv (v3.3.3 => v3.4.0)
- symfony/translation (v3.4.27 => v3.4.28)
- nesbot/carbon (1.37.1 => 1.38.4)
- adldap2/adldap2 (v10.0.10 => v10.0.11)
- symfony/debug (v3.4.27 => v3.4.28)
- symfony/console (v3.4.27 => v3.4.28)
- symfony/finder (v3.4.27 => v3.4.28)
- doctrine/lexer (v1.0.1 => 1.0.2)


### [4.1.2](https://github.com/munkireport/munkireport-php/compare/v4.1.1...v4.1.2) (May 07, 2019)

Small update to fix the deleting of client machines.

FIXES
- Fix deleting of machines

MODULE UPDATES
- managedinstalls (v1.1 => v1.2)

DEPENDENCY UPDATES
- nesbot/carbon (1.36.2 => 1.37.1)
- adldap2/adldap2 (v10.0.8 => v10.0.10)


### [4.1.1](https://github.com/munkireport/munkireport-php/compare/v4.1.0...v4.1.1) (May 01, 2019)

FIXES
- Fix Widget Gallery uninitialized variables
- Fix Sophos module uninitialized variables
- Fix Caching module uninitialized variables

MODULE UPDATES
- caching (v1.1 => v1.2)
- fan_temps (v1.3 => V1.4)
- munkireportinfo (v1.2 => v1.3)
- sophos (v1.1 => v1.2)

### [4.1.0](https://github.com/munkireport/munkireport-php/compare/v4.0.2...v4.1.0) (April 11, 2019)

FIXES
- Change 'memory' localization name (#1210) @tuxudo
- Error handling for deleting machines.
- Added Time Machine errors back to client summary (#1211) @tuxudo
- Localize Delete button on business unit management (#1141) @tuxudo
- Added additional OneLogin supported parameters (#1214)
- Update SAML (#1244)

NEW FEATURES
- 'f' hotkey for filter modal (#1150) @tuxudo
- Added logging for adldap2
- Added AirPlay to vendors (#1234) @tuxudo
- Make showing help link default to true (#1228) @WardsParadox
- Add Widget Gallery (#1140) @tuxudo

MODULE UPDATES
- machine (v1.1 => v1.2)
- disk_report (v1.2 => v1.3)
- displays_info (v1.3 => v1.3.3)
- fan_temps (v1.1 => v1.3)
- ibridge (v1.2 => v1.4)
- munki_facts (v1.2 => v1.3)
- munkireportinfo (v1.1 => v1.2)
- profile (v1.1 => v1.2)
- softwareupdate (v1.1 => v1.2)
- supported_os (v1.3 => v1.5)
- timemachine (v1.2 => v1.3)

### [4.0.2](https://github.com/munkireport/munkireport-php/compare/v4.0.1...v4.0.2) (February 08, 2019)

FIXES
- Updated config_to_env conversion script
- Added manager role

NEW FEATURES
- Docker builds PHP v7.3
- Added SSL options (to address #1217)
- Added mdm_status module


### [4.0.1](https://github.com/munkireport/munkireport-php/compare/v4.0.0...v4.0.1) (December 21, 2018)

FIXES
- `APPS_TO_TRACK` now working again due to an upgrade to the `inventory` module.
- Added `.env.example` to the `.zip` and `tar.gz` downloads.
CHANGES
- Added support for local widgets.
- Removed `custom_folder` as a method to load custom widgets. As a replacement, use `local/views/widgets`. Or override `WIDGET_SEARCH_PATHS` with your own paths.
- Hotkeys are displayed in the dashboard dropdown if more than one dashboard is configured
- There are 2 new widgets added to the `munkireport` module that show the top 10 error and warning messages.

### [4.0.0](https://github.com/munkireport/munkireport-php/compare/v3.3.1...v4.0.0) (December 18, 2018)

IMPORTANT CHANGES

- This release changes the entire configuration system to `.env`. This means that `config.php` and `config_default.php` are no longer in use.
- There is a new directory called `local` that is used for user accounts, dashboards, certificates and module specific yaml files.

Please read https://github.com/munkireport/munkireport-php/wiki/How-to-Upgrade-Versions#version-3x---4x for more information

FIXES
- Fixing the Listing title (#1194) @joncrain
- Removing unnecessary data (#1195) @joncrain
- Including preset curl options by default (#1197) @joncrain
- Added local users config info to .env.example and README.md (#1204) @fridomac

OTHER CHANGES
- SAML attribute mapping for user and groups
- The `adldap2/adldap2` and `onelogin/php-saml` libraries are updated to the latest version and included in the default installation.

### [3.3.1](https://github.com/munkireport/munkireport-php/compare/v3.3.0...v3.3.1) (November 27, 2018)

FIXES
- `build/add_module.sh`

CHANGES
- Update docker files (#1192)
- Moved config items to their respective modules

NOTES
- Docker users: renamed MR_SITENAME to SITENAME and MR_MODULES to MODULES

### [3.3.0](https://github.com/munkireport/munkireport-php/compare/v3.2.6...v3.3.0) (November 20, 2018)

FIXES
- Fix Database sizing (#1177)

NEW FEATURES
- Add module search path (#1187)
- Modules are split off into separate repos
- Added composer-merge plugin (allows for local composer.json file)

### [3.2.6](https://github.com/munkireport/munkireport-php/compare/v3.2.5...v3.2.6) (November 08, 2018)

FIXES
- detectx I ❤️ u

### [3.2.5](https://github.com/munkireport/munkireport-php/compare/v3.2.4...v3.2.5) (November 07, 2018)

FIXES
- Fixed detectx migration - again

### [3.2.4](https://github.com/munkireport/munkireport-php/compare/v3.2.3...v3.2.4) (November 06, 2018)

FIXES
- Fixed detectx migration

### [3.2.3](https://github.com/munkireport/munkireport-php/compare/v3.2.2...v3.2.3) (November 03, 2018)

FIXES
- Add magic_keyboard_with_numeric_keypad to bluetooth locale
- Removed app_usage widget from managedinstalls... (#1138) @tuxudo
- Detectx patch (#1137) @WardsParadox
- Localize ARD Text on client summary (#1132) @tuxudo
- Added space to paths that had space removed. (#1124) @poundbangbash
- Updated current highest OS (#1123) @tuxudo
- Fixed menu sorting case sensitivity (#1121) @tuxudo
- Corrected devtools install script (#1114) @tuxudo
- Added support for machine groups (#1144) @tuxudo
- Remove group check from AuthSaml.php
- Added machine group filtering to munki_facts widget (#1158) @tuxudo
- Add machine group filtering (#1157) @tuxudo
- Minor database fixes
- Add port to MySQL dsn
- Fixed clickthrough button URLs (#1153) @tuxudo
- Fixed storage listing loading column count (#1151) @tuxudo
- Fixed for Macs without Metal support (#1169) @tuxudo

NEW FEATURES
- Show machine group name instead of number (#1129) @tuxudo
- Added version of database server (#1128) @WardsParadox
- Add support for default theme (#1131) @tuxudo
- Added more USB devices (#1165) @tuxudo

NEW MODULES
- Added iBridge module (#1116) @tuxudo

### [3.2.2](https://github.com/munkireport/munkireport-php/compare/v3.2.1...v3.2.2) (July 08, 2018)

FIXES
- SentinelOne uninstallers
- MigrationsPending (#1108) @mosen
- Smart widget ui fix (#1107) @poundbangbash

### [3.2.1](https://github.com/munkireport/munkireport-php/compare/v3.2.0...v3.2.1) (July 05, 2018)

FIXES
- Addressed duplication of ARD groups. (#1106) @poundbangbash
- Security module fix ard_users - add nested ard_groups (#1105) @poundbangbash
- Fix for issue #1102 @tuxudo
- Improvements to Sophos module (#1100) @rickheil

### [3.2.0](https://github.com/munkireport/munkireport-php/compare/v3.1.1...v3.2.0) (July 01, 2018)

FIXES
- detectx @WardsParadox
- backup2go list view fix (#1056) @pnerum
- Make the SIP check more resilient (#1063) @barn-stripe
- Drop Down the Login Page (#1062) @gmarnin
- Add disable_sso_sls_verify config option (#1007) @sphen13
- Fixed localized event message (#1051) @tuxudo
- Actually fix security.py module (#1068) @barn-stripe
- Fix for Find My Macs widget not supporting business units (#1067) @tuxudo
- Fixed apps tab order (#1069) @tuxudo
- Added basic crypt checkin url (#1077) @WardsParadox
- Hotfix/migration patches (#1087) @mosen
- Fix for no workflow title (#1082) @tuxudo
- AD Auth (#1096) @mosen
- Removing Machine-Groups/Business Units (#1097) @mosen
- Changed Extensions add teamid column method (#1098) @tuxudo

NEW FEATURES
- Help button @danner26
- phpdotenv support (#1032) @mosen
- Added advanced SAML settings
- Adds Metal support to GPU module (#1058) @tuxudo
- Added ability to skip bundle IDs in Appusage module (#1055) @tuxudo
- Profile count report (#1030) @poundbangbash
- Added local network ip option for vnc and ssh links (#1029) @poundbangbash
- Added another USB device type (#1022) @tuxudo
- Add column to filter bound status against (#1065) @poundbangbash
- Add UID to reportdata, user_sessions module, localadmin module @poundbangbash
- Add check network based users added to com.apple.local.ard_interact (#1071) @poundbangbash
- Free ipa support (#1080)
- Implement db size calcs (#1094)

NEW MODULES
- Devtools Module (#913) @tuxudo
- Highest Supported OS module (#834) @tuxudo
- Sophos Anti-virus module (#1093) @rickheil
- SentinelOne (#1072) @poundbangbash
- SentinelOne Quarantine module (@1073) @poundbangbash

### [3.1.1](https://github.com/munkireport/munkireport-php/compare/v3.1.0...v3.1.1) (March 23, 2018)

FIXES
- filevault escrow migration

NEW FEATURES
- Travis CI integration (#1031) @danner26
- Added support for server-side IP filtering (#990) @danner26

### [3.1.0](https://github.com/munkireport/munkireport-php/compare/v3.0.2...v3.1.0) (March 21, 2018)

FIXES
- Totalsize of disk in client detail
- Updated MySQL default config example
- munkiinfo module
- Adjusted security report loading data (#983) @tuxudo
- Fix DeployStudio migration (#982) @tuxudo
- Fixed DeployStudio processing on MR3 (#985) @tuxudo
- Add option to use current username for ssh link (#976) @poundbangbash
- Check for no Bluetooth in BT module (#992) @tuxudo
- Fixed postflight error in homebrew modules (#991) @tuxudo
- Migration adjustment for nullable values. (#973) @poundbangbash
- Fixed vendor column (#998) @tuxudo
- Docker (#1003) @sphen13
- Remove index.php .htacces and web.config from src root
- Fix certificate migration
- Add fv key encryption migration
- Add filter to getLocalAdmins
- Extensions module - Added variable declaration (#1017) @poundbangbash

NEW FEATURES
- Add optional recursive group searching to AD auth
- Added widget for 32-bit apps (#1021) @tuxudo
- Added support for different ship to in GSX (#1028)

### [3.0.2](https://github.com/munkireport/munkireport-php/compare/v3.0.1...v3.0.2) (February 15, 2018)

More bugfixes, mostly migration related.

FIXES
- Fixed migrations for SQLite (#971)
- Added indexes for the tag, usb, fonts, network_shares, homebrew and servermetrics modules
- Improved Dockerfile


### [3.0.1](https://github.com/munkireport/munkireport-php/compare/v3.0.0...v3.0.1) (February 14, 2018)

This is a small bugfix release that fixes some issues with the 3.0.0 release.

FIXES
- Fixed certificate warnings in events (#959) @tuxudo
- Hide zero blocks on disk widgets (#958) @tuxudo
- Security ssh detection update (#943) @poundbangbash
- Smart stats report title fix (#942) @poundbangbash
- Changed power columns to be nullable in migration (#955) @tuxudo
- Smart stats widget title localization fix (#961) @poundbangbash
- Fixed WiFi nullable migration (#957) @tuxudo
- Fix migration files @poundbangbash
- Disk report error fix (#965) @tuxudo
- Extensions migration name fix (#968) @poundbangbash
- Adjust database log view to use theme for readability. (#963) @poundbangbash
- Fix bad datatype in usage_stats migration (#956) @tuxudo

NEW FEATURES
- Extension module TeamID breakout (#953) @poundbangbash
- Create widget for os build breakdown and add it to the client report (#951) @AaronBurchfield
- Update Dockerfile for php7.2

### [3.0.0](https://github.com/munkireport/munkireport-php/compare/v2.15.2...v3.0.0) (February 07, 2018)

The third major version of munkireport attempts to solve a couple of longstanding issues concerning security, dependency management and database management. We're moving slowly to using eloquent as replacement for the KISS database abstraction. A ton of work has been done by @mosen to make this all work.

Most notable changes are:

- Minimum PHP version is 7.0
- Document root is now in a directory called `/public`. The best practice is to serve your munkireport instance from this directory or make a symlink to this directory.
- If you install munkireport via the commandline, you need to install all dependencies first via [composer](https://getcomposer.org)
- There is a new database configuration format `$conf['connection']`. See `config_default.php` on how to use it.
- Database migrations (also the initial ones) are not running automatically. You need to run migrations via the commandline tool `php database/migrate.php`

See also the [Upgrade v3 guide](https://github.com/munkireport/munkireport-php/wiki/How-to-Upgrade-to-v3) and [Quick demo v3](https://github.com/munkireport/munkireport-php/wiki/Quick-demo-V3)

FIXES
- Certificate Module fix for multiple certs with same name (#893) @sphen13
- Misc UI fixes (#885) @tuxudo
- Fix vendors for 10.13 (#902) @tuxudo
- Fix localization in client tab (#903) @tuxudo
- Use CFPreferences to read BaseUrl and Passphrase. (#919) @MagerValp
- Misc migration fixes
- AuthLDAP fixed (#944)
- Recaptcha uses proxy settings
- Database info fixed
- Installer postflight script (#949) @MagerValp

NEW FEATURES
- NVMe Support in SMART Stats module (#895) @tuxudo
- Update Network Info script to now pull Tunnel adapter information (#897) @jbaker10
- Sort machine groups by name (#898) @choules
- New module: Munki-facts (#850) @nathanperkins and @poundbangbash
- New module: Usage Stats module (#843) @tuxudo
- New module: Applications Module (#917) @tuxudo
- New module: DetectX Module (#916) @WardsParadox
- SAML support, see also [SAML authentication](https://github.com/munkireport/munkireport-php/wiki/SAML-authentication)
- Added filesystem widget (#886) @tuxudo
- Maintenance Mode, see also [Maintenance Mode](https://github.com/munkireport/munkireport-php/wiki/Maintenance-Mode)
- German translation (#941) @fridomac

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
