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

# Script by rtrouton
# Confirm current FileVault status. If on or in the process of encrypting exit 1
CORESTORAGESTATUS="/private/tmp/corestorage.txt"
ENCRYPTSTATUS="/private/tmp/encrypt_status.txt"
ENCRYPTDIRECTION="/private/tmp/encrypt_direction.txt"

# Get number of CoreStorage devices. The egrep pattern used later in the script
# uses this information to only report on the first encrypted drive, which should
# be the boot drive.
#
# Credit to Mike Osterman for identifying this problem in the original version of
# the script and finding a fix for it.

# Store the output of diskutil cs list in a temporary file
diskutil cs list >> $CORESTORAGESTATUS

DEVICE_COUNT=`grep -E "^CoreStorage logical volume groups" $CORESTORAGESTATUS| awk '{print $5}' | sed -e's/(//'`

EGREP_STRING=""
if [ "$DEVICE_COUNT" != "1" ]; then
  EGREP_STRING="^\| *"
fi

CONTEXT=`grep -E "$EGREP_STRING\Encryption Context" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $3}'`
ENCRYPTIONEXTENTS=`grep -E "$EGREP_STRING\Has Encrypted Extents" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $4}'`
ENCRYPTION=`grep -E "$EGREP_STRING\Encryption Type" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $3}'`
CONVERTED=`grep -E "$EGREP_STRING\Size \(Converted\)" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $5, $6}'`
SIZE=`grep -E "$EGREP_STRING\Size \(Total\)" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $5, $6}'`

/usr/sbin/diskutil cs list >> $CORESTORAGESTATUS
  
# If the Mac does not have any CoreStorage volumes, the following message is displayed: FileVault 2 Encryption Not Yet Enabled
    if grep -iE 'No CoreStorage' $CORESTORAGESTATUS 1>/dev/null; then
       echo "FileVault 2 Encryption Not Yet Enabled\n"
    fi

# This section does 10.9-specific checking of the Mac's FileVault 2 status
if [[ ${osvers} -ge 13 ]]; then
  CONVERTED=`grep -E "\Conversion \Progress" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $3}'`
fi

# This section does 10.8-10.9 specific checking of the Mac's FileVault 2 status
if [ "$ENCRYPTIONEXTENTS" = "Yes" ]; then
  grep -E "$EGREP_STRING\Fully Secure" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $3}' >> $ENCRYPTSTATUS
  if grep -iq 'Yes' $ENCRYPTSTATUS; then 
    # echo "FileVault 2 Encryption Complete"
    echo "\n *** FileVault is already turned on, no need to run this script, exiting ***\n"; exit 1
  elif  grep -iq 'No' $ENCRYPTSTATUS; then
    grep -E "$EGREP_STRING\Conversion Direction" $CORESTORAGESTATUS | sed -e's/\|//' | awk '{print $3}' >> $ENCRYPTDIRECTION
    if grep -iq 'forward' $ENCRYPTDIRECTION; then
       # echo "FileVault 2 Encryption Proceeding. $CONVERTED of $SIZE Encrypted"
       echo "\n *** FileVault 2 Encryption Proceeding. $CONVERTED of $SIZE Encrypted, exiting ***\n"; exit 1
    else
      if grep -iq 'backward' $ENCRYPTDIRECTION; then
        echo "FileVault 2 Decryption Proceeding. $CONVERTED of $SIZE Decrypted"
      elif grep -iq '-none-' $ENCRYPTDIRECTION; then
        # echo "FileVault 2 Decryption Completed"
        echo "\n *** FileVault 2 Decryption Completed. Reboot the Mac and rerun this script. exiting ***\n"; exit 1
      fi
    fi
  fi  
fi

if [ "$ENCRYPTIONEXTENTS" = "No" ]; then
    echo "FileVault 2 Encryption Not Enabled"
fi

# Remove the temp files created during the script
rm -f $CORESTORAGESTATUS $ENCRYPTSTATUS $ENCRYPTDIRECTION

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