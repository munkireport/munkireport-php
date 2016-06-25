whd module
==================

Pull data from Web Help Desk's Assets database and
optionally pushes back data.

Requires functioning Web Help Desk (WHD) installation
with read access via API enabled. Some features 
require API write access to Assets.

Some of WHD's Asset fields can be updated with information
from MunkiReport. This functionality is disabled by
default, but when enabled the fields with (writable) 
next to them are updated when the machine checks in.

For more information on how to setup the whd module, see
the config_default.php file.

The following information is stored in the table:

* assetNumber - WHD's unique Asset number for the serial
* notes - notes about the Asset, BLOB
* roomName - Name of room where Asset is
* modelName - WHD's Asset model name
* macAddress - MAC address of Asset
* networkAddress - IP address of Asset (writable)
* networkName - Hostname of Asset (writable)
* purchaseDate - Date of Asset's purchase (writable)
* locationid - WHD's Location ID for Asset
* version - Asset's version (writable)
* address - Asset's address
* city - Asset's city
* postalCode - Asset's postal code
* state - Asset's state
* assetType - Asset type
* client - Who is assigned Asset
* isReservable - Is Asset reservable
* leaseExpirationDate - Date Asset's lease expires
* warrantyExpirationDate - Date Asset's warranty expires (writable)
* isNotesVisibleToClients - Are Asset's notes visible to clients
* isDeleted - Is Asset deleted from WHD
* clientEmail - Client's email address
* clientName - Client's full name
* clientNotes - Notes about the client, BLOB
* clientPhone - Client's phone number
* clientPhone2 - Client's second phone number
* clientdepartment - Client's department
* clientaddress - Client's address
* clientcity - Client's city
* clientlocationName - Client's location name
* clientpostalCode - Client's postal code
* clientstate - Client's state
* clientroom - Client's room
* clientcompanyName - Client's company name
* locaddress - Location's address
* loccity - Location's city
* loccountry - Location's country
* locdomainName - Location's domain name
* locfax - Location's fax number
* loclocationName - Location's name
* locnote - Notes about the Location, BLOB
* locphone - Location's phone number
* locphone2 - Location's second phone number
* locpostalCode - Location's postal code
* locstate - Location's state
* loccolor - Location's color
* locbusinessZone - Location's business zone
