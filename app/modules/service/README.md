Service module
===============

Get status of selected OS X Server services.
This module collects data on the state of services by running serveradmin status command.

These services are queried:

SERVICES="addressbook afp caching calendar
certs collabd devicemgr dhcp dirserv dns ftp
history jabber mail netboot network nfs radius
san smb swupdate timemachine vpn web wiki xcode"

The results are stored in the table:

* id - Unique id
* serial_number - Serial Number
* service_name - Name of service
* service_state - State of service
* timestamp - Timestamp of last update