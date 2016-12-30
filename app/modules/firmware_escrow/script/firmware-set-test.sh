#!/bin/sh

#set -x

# @gmarnin 12-30-2016

echo "Test script to get sample random password and date into MR. Does not set Firmware password yet"
# echo "***** Sample Firmware Script | Escrows Firmware Password to MunkiReport *****"

# firmwarepasswd only works on 10.10+ so bail if on 10.9 or lower
# add check to test if firmware is already set
# add check to make sure MR is reachable 
# add check to make confirm password matches what was set
# let the users write their own script

# must run this script as root
	if [ $USER != root ]; then
		echo "\n *** Please run this script with root privileges, exiting ***\n"; exit 1
	fi


# generate random password. 
# also works: openssl rand -base64 5 
Password=`cat /dev/urandom | base64 | tr -dc 'a-zA-Z0-9' | fold -w 6 | head -n 1`

EnabledDate=`date +%Y-%m-%d\ %H:%M:%S`

# tmp is not a good location but works for testing
printf "EnabledDate = $EnabledDate\nFirmwarePassword = $Password\n" > /tmp/firmware_results.txt

# echo "Password secret is: $Password"

echo "Submitting Firmware Escrow Report"

# add firmware_escrow pref to MunkiReport configuration plist
/usr/bin/defaults write /Library/Preferences/MunkiReport ReportItems -dict-add firmware_escrow /tmp/firmware_results.txt

# submitting firmware escrow report to Munkireport server
/usr/local/munki/postflight

# remove firmware_escrow pref from the MunkiReport configuration plist
/usr/libexec/PlistBuddy -c "Delete :ReportItems:firmware_escrow" /Library/Preferences/MunkiReport.plist 

echo ""
echo "Please confirm the firmware password is on the MunkiReport server."


exit 0