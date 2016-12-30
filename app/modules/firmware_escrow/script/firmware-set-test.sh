#!/bin/sh

# set -x

# @gmarnin 12-30-2016

echo ""
echo "Test script to get sample random password and date into MR. Does not set Firmware password yet"
# echo "***** Sample Firmware Script | Escrows Firmware Password to MunkiReport *****"

# to do:
# add firmwarepasswd commands to set password
# add check to make confirm password matches what was set
# let the users write their own script

# handful of sanity checks:

# must run this script as root
	if [ $USER != root ]; then
		echo "\n *** Please run this script with root privileges, exiting ***\n"; exit 1
	fi

# firmwarepasswd only works on 10.10+ so bail if on 10.9 or lower
OS=`/usr/bin/sw_vers -productVersion | cut -d . -f 2`

if [[ "$OS" -ge "10" ]]; then
# check if firmware is already set. bail if it is.
# echo "Our OS version is $OS"
PasswordSet=`/usr/sbin/firmwarepasswd -check`

	if [[ "$PasswordSet" == "Password Enabled: Yes" ]]; then
		echo "Firmware password is already set"; exit 1
	else
		echo "Firmware password is not set"
	fi
else
	echo "macOS version is not supported. Requires 10.10 +"; exit 1
fi

# check we have an ip address
allPorts=$(/usr/sbin/networksetup -listallhardwareports | awk -F' ' '/Device:/{print $NF}')

while read Port; do
    if [[ $(ifconfig "$Port" 2>/dev/null | awk '/status:/{print $NF}') == "active" ]]; then
        ActivePort="$Port"
        break
    fi
done  < <(printf '%s\n' "$allPorts")

MacIP=`/usr/sbin/ipconfig getifaddr $ActivePort | awk  'BEGIN { FS = "." } ; { print $1"." }'`
	# echo "IP address is: $MacIP"	
	if [ "${MacIP}" != "192." ]; then
		echo "\n *** Mac isn't on network based on IP. Need to be on network to escrow password. exiting ***\n"; exit 1
	else 
	echo "IP address looks good!"
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