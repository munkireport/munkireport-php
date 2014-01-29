<p>FileVault 2 Escrow module</p>

<p>Provides the information that the fdesetup -outputplist flag returns</p>

<p>The table provides the following information per client:</p>

<p>EnabledDate - The data FileVault was enabled 
EnabledUser - Users added to the EFI login (authorized to unlock the drive)
HardwareUUID - The hardware UUID
LVGUUID - (CoreStorage) Logical Volume Group UUID
LVUUID - (CoreStorage) Logical Volume UUID
PVUUID - (CoreStorage) Physical Volume UUID
RecoveryKey -  The personal recovery key
SerialNumber - The serial number of the Mac
Also added is HddSerial - The serial number of the hard drive </p>

<p>Remarks</p>

<p>The workflow:</p>

<ol>
<li>Manually run the script that enables FileVault.</li>
</ol>

<p>A sample script can be found in /modules/filevault_escrow/script/
    Note: This script isn't automatically downloaded by the client. Feel free to customize this script for your own needs. </p>

<p>The script preforms some sanity checks such as making sure the OS is at least 10.8, munkireport is installed,
FileVault isn't already on and a recovery partition present.</p>

<p>The script saves the FileVault escrow data to /tmp/fvresults.plist
The file will automatically be deleted when the Mac is rebooted. </p>

<p>The script sets a filevault_escrow preferences to MunkiReport configuration and removes the preferences after the data is uploaded</p>

<ol>
<li><p>Reboot the Mac to start the encryption</p></li>
<li><p>FileVault status and the account name(s) of the enabled users will not be collected until the next munki run after the reboot</p></li>
</ol>

<p>To do</p>

<p>Add to the FileVault script to read the database and confirm the recovery key is safe before the Mac is rebooted.</p>
