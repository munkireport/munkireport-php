Munki reporting module
==============

Provides info about munki

* timestamp (string) (string) datetime 
* runstate'] = (string) 'done' legacy field
* runtype'] = (string) one of auto, manualcheck, installwithnologout, checkandinstallatstartup and logoutinstall
* starttime (string) DST datetime 
* endtime (string) DST datetime 
* version (string) Munki version
* errors (int) Amount of errors
* warnings (int) Amount of warnings
* manifestname (string) name of the manifest
* managedinstalls (int) Total packages
* pendinginstalls (int)  To be installed
* installresults (int) Amount of installed packages
* removalresults (int) Amount of removed packages
* failedinstalls (int) Amount of packages that failed to install
* pendingremovals (int) Amount of packages to be removed
* itemstoinstall (int) Amount of munki packages to install
* appleupdates (int) Amount of Apple updates to install
* report_plist (blob) Serialized, compressed full report

Remarks
---

* report_plist is a big binary blob that cannot be searched (we should fix that)
