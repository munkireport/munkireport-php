Software Update module
==============

Reports on the status of software updates on the client

* automaticcheckenabled - int - automatic checks enabled
* automaticdownload - int - automatic downloads enabled
* configdatainstall - int - automatic downloads of XProtect and GateKeeper data enabled
* criticalupdateinstall - int - automatica downloads of critical updates enabled
* lastattemptsystemversion - varchar(255) - last version and build of OS X that attempted an update
* lastbackgroundccdsuccessfuldate - varchar(255) - last backgroud update successful date
* lastbackgroundsuccessfuldate - varchar(255) - last backgroud update successful date
* lastfullsuccessfuldate - varchar(255) - date of last successful update
* lastrecommendedupdatesavailable - int - number of last recommended updates
* lastresultcode - int - last result code from update
* lastsessionsuccessful - int - last session successful
* lastsuccessfuldate - varchar(255) - last successful session date
* lastupdatesavailable - int - number of last available updates
* skiplocalcdn - int - skip the local CDN when downloading updates
* recommendedupdates - varchar(255) - names of items needs updated
* mrxprotect - varchar(255) - date of last XProtect update installation
* catalogurl - varchar(255) - current catalog URL, default is blank


###Note 
Blank data entries within the listing or client tab mean the key is either not supported on that version of macOS or the key is set to the default vaule. When set to the default vaule, the state is not written to disk.