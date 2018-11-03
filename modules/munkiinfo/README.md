Munki Info module
================

An extension to the munkireport module as such that module is a requirement. Contains additional munki preferences and widgets.

The following information is stored in the munkiinfo table:

_NOTE:_ the names line up with [Munki Preferences](https://github.com/munki/munki/wiki/Preferences) as such no description is given


* AppleSoftwareUpdatesOnly
* CatalogURL
* ClientCertificatePath
* ClientIdentifier
* ClientKeyPath
* ClientResourceURL
* ClientResourcesFilename
* DaysBetweenNotifications
* FollowHTTPRedirects
* HelpURL
* IconURL
* InstallAppleSoftwareUpdates
* InstallRequiresLogout
* LocalOnlyManifest
* LogFile
* LogToSyslog
* LoggingLevel
* MSUDebugLogEnabled
* MSULogEnabled
* ManagedInstallDir
* ManifestURL
* PackageURL
* PackageVerificationMode
* ShowRemovalDetail
* SoftwareRepoCACertificate
* SoftwareRepoCAPath
* SoftwareRepoURL
* SoftwareUpdateServerURL
* SuppressAutoInstall
* SuppressLoginwindowInstall
* SuppressStopButtonOnInstall
* SuppressUserNotification
* UnattendedAppleUpdates
* UseClientCertificate
* UseClientCertificateCNAsClientIdentifier


These are the only non-ManagedInstall preference values stored in this module

* AppleCatalogURL - This is the catalog that `/usr/sbin/softwareupdate` will use to find updates
* munkiprotocol - This is the url scheme for the `SoftwareRepoURL` key. This is used for widgets. 
* middlewarename and middlewareversion - These are displayed only if middleware is installed. Version relies on a get_version() function in your middleware script.
