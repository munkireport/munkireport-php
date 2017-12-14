#!/bin/sh

echo ""
echo "***** Sample FileVault Kickoff Script | Escrows FileVault Data to MunkiReport PHP 2 *****"

# Handful of sanity checks:

# Must run this script as root
	if [ $USER != root ]; then
		echo "\n *** Please run this script with root privileges, exiting ***\n"; exit 1
	fi

# Confirm there is a recovery partition present 
# recoveryHDPresent=`/usr/sbin/diskutil list | grep "Recovery HD" | awk '{ print $3, $4 }'`
# This covers Fusions and non Fusion drives
recoveryHDPresent=`/usr/sbin/diskutil list | grep "Apple_Boot" | awk '{ print $2 }'`
	if [ "$recoveryHDPresent" = "" ]; then
		echo "\n *** Recovery Partition not found. FileVault requires the Recovery Partition, exiting ***\n"; exit 1
	fi

# Check if BootCamp partition is present
bootcamp_detect=$(/usr/sbin/diskutil list | grep -c "Microsoft Basic Data")
	if [ "${bootcamp_detect}" == "1" ]; then
        echo "\n *** Warning: BootCamp partition detected. FileVault doesn't encrypt BootCamp partitions ***\n"
	fi 

# Confirm we are running at least 10.8
osversionlong=$(uname -r)
osvers=${osversionlong/.*/}
    if [ ${osvers} -ge 12 ]; then
    	echo ""
    else
    	echo "\n *** Upgrade time! You need at least 10.8 to run this script, exiting ***\n"; exit 1
    fi

# Confirm Munkireport postflight is installed
if [ -e /Library/Preferences/MunkiReport.plist ]; then 
echo ""
else
	echo "\n *** Munkireport is not installed exiting ***\n"; exit 1
fi

# Confirm current FileVault status. If on or in the process of encrypting exit 1
diskutil info / | grep -q 'Encrypted:.*Yes'
if [ $? -eq 0 ]; then
	echo "\n *** Disk already encrypted, exiting.. ***\n"; exit 1
fi

# End of sanity checks


# Add the primary user's account to FileVault
# Use th GUI (after the Mac reboots) to enable more than one account to use FileVault 
echo "Enter the primary user NetID for this Mac:"		
read clientid

## Kickoff FileVault ##
# fdesetup will ask for passwords for all user accounts listed below. 
# Replace AnotherAccount with a real account
# fvresults.plist saved to /tmp so it automagically gets destroyed at reboot
/usr/bin/fdesetup enable -user "$clientid" -usertoadd AnotherAccount -outputplist > /tmp/fvresults.plist

# Add hard drive serial number to /tmp/fvresults.plist just in case the hard drive separates from the Mac
HDD_SERIAL=`/usr/sbin/system_profiler SPSerialATADataType | grep "Serial Number:" | awk '{print $3}' | sed '1 ! d'`

# Convert plists binary .plist into XML so it can be easily edited:
/usr/bin/plutil -convert xml1 /tmp/fvresults.plist

/usr/bin/defaults write /tmp/fvresults HddSerial $HDD_SERIAL

echo "Submitting FileVault Escrow Report"

# Add filevault_escrow pref to MunkiReport configuration
/usr/bin/defaults write /Library/Preferences/MunkiReport ReportItems -dict-add filevault_escrow /tmp/fvresults.plist

# Submitting FileVault Escrow report to Munkireport server
/usr/local/munki/postflight

# Remove filevault_escrow pref from the MunkiReport configuration plist
/usr/libexec/PlistBuddy -c "Delete :ReportItems:filevault_escrow" /Library/Preferences/MunkiReport.plist 

echo "Please confirm the Recovery Key is on the MunkiReport server and then reboot to complete the process."

exit 0