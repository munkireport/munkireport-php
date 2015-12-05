Filevault Status module
=======================

Gathers filevault users information by running`fdesetup list`

The table provides the following information per 'machine':

* id (int) Unique id
* serial_number (string) Serial Number
* filevault_status (string) deprecated status	
* filevault_users (string) filevault users

Remarks
----

* Filevault status is deprecated, the disk_report module has info on encryption status