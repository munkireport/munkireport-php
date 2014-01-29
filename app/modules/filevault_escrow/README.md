# FileVault 2 Escrow module

Provides the information that the `fdesetup -outputplist` flag returns

The table provides the following information per client:

+ EnabledDate - The data FileVault was enabled
+ EnabledUser - Users added to the EFI login (authorized to unlock the drive)
+ HardwareUUID - The hardware UUID
+ LVGUUID - (CoreStorage) Logical Volume Group UUID
+ LVUUID - (CoreStorage) Logical Volume UUID
+ PVUUID - (CoreStorage) Physical Volume UUID
+ RecoveryKey -  The personal recovery key
+ SerialNumber - The serial number of the Mac
+ Also added is HddSerial - The serial number of the hard drive 

#Remarks

The workflow:


1. Manually run the script that enables FileVault  
   A sample script can be found in /app/modules/filevault_escrow/script/  
   Note: This script isn't automatically downloaded by the client. Feel free to customize this script for your own needs.  

 The sample script does the following:  
 * Preforms some sanity checks such as making sure the OS is at least 10.8, Munkireport is installed,
FileVault isn't already on and a recovery partition present.  

 * Saves the FileVault escrow data to /tmp/fvresults.plist  
This file will automatically be deleted when the Mac is rebooted  

 * Sets a filevault_escrow key to the MunkiReport.plist preference and removes the key after the escrow data is uploaded   

 * Uploads the escrow data by running /usr/local/munki/postflight
 

2. Reboot the Mac to start the encryption  
    * FileVault status and the account name(s) of the enabled users will not be collected until the next munki run after the reboot


# To do

+ Add a section to the FileVault kickoff script to read the database and confirm the recovery key is safe before the Mac is rebooted.
