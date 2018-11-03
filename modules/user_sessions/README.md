User Sessions module
==============

Gathers logins, logout, shut downs, and reboots on the client computer


Database:
* event - varchar(255) - event type 
* time - int - UNIX time of event occurrence
* user - varchar(255) - user name associated with event
* ssh_remove - varchar(255) - IP address of the remote SSH user

Module by @tuxudo, script by @clburlison and @pudquick (frogor)