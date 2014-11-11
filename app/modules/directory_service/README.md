Directory Service module
==============

Collects the name of first domain listed in `dscl`, AD comments for the machine record and AD plugin options from `dsconfigad -show`

The table 'directoryservice' stores the following:

* which_directory_service (string) Name of the first listed active plugin
* directory_service_comments (string) Comments, if any, stored on the Active directory server   
* adforest (string) Active directory forest the computer is part of
* addomain (string) Active directory the computer is bound to
* computeraccount (string) The "computerid" in the specified Domain
* createmobileaccount (bool) Support for offline logon. Enabled = 1, Disabled = 0
* requireconfirmation (bool) Warn the user when a mobile account is going to be created. Enabled = 1, Disabled = 0
* forcehomeinstartup (bool) Forces all home directories to be local to the computer. Enabled = 1, Disabled = 0
* mounthomeassharepoint (bool) Enable or disable mounting of the network home as a sharepoint. Enabled = 1, Disabled = 0
* usewindowsuncpathforhome (bool) Whether the plugin uses the UNC specified in the Active Directory when mounting the network home. Enabled = 1, Disabled = 0
* networkprotocoltobeused (string) How a home directory is mounted on the desktop. Possible values [afp | smb | nfs]
* defaultusershell (string) Default user shell
* mappinguidtoattribute (string) Attribute to be used for the UID of the user
* mappingusergidtoattribute (string) Attribute to be used for the GID of the user
* mappinggroupgidtoattr (string) Attribute to be used for the GID of the group
* generatekerberosauth (bool) Wether a Kerberos authority was generated during bind. Enabled = 1, Disabled = 0
* preferreddomaincontroller (string) Preferred server for all Directory lookups and authentications
* allowedadmingroups (string) Groups with local administrative privileges on the computer
* authenticationfromanydomain (bool) Allow authentication from any domain in the forest. Enabled = 1, Disabled = 0
* packetsigning (string) Packet signing. Possible values [allow | disable | require]
* packetencryption (string) Packet encryption. Possible values [allow | disable | require | ssl]
* passwordchangeinterval (int)
* restrictdynamicdnsupdates (string) Network interfaces with restricted Dynamic DNS updates.
* namespacemode (string) Primary account username naming convention. Possible values [forest | domain]

Remarks
---

* If no data has been collected by `dsconfigad -show` the boolean values will show disabled in the client view Directory Services tab
* Currently the data collected from the bound to an Open Directory server is limited to bound or not (stored in which_directory_service)
* Third party AD plugins data is not being collected