#!/bin/sh

# FindMyMac information

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
findmymac_manfiest_file="$DIR/cache/findmymac.txt"
findmymac_raw_data="$DIR/cache/findmymac_raw_data.plist"

#### FindMyMac Manifest Information ####
fmmdata=$(/usr/sbin/nvram -x -p | /usr/bin/sed -n '/fmm-mobileme-token/,/<\/data>/p' | /usr/bin/grep -v 'data\|key' | /usr/bin/tr -d '\t' | /usr/bin/tr -d '\n')
if [[ -z $fmmdata ]]
then
    echo "Status = Disabled" > "$findmymac_manfiest_file"
else
    echo ${fmmdata} | /usr/bin/base64 --decode > $findmymac_raw_data
    echo "Status = Enabled" > "$findmymac_manfiest_file"

    email=$(/usr/libexec/PlistBuddy -c "Print username" $findmymac_raw_data)
    echo "Email = $email" >> "$findmymac_manfiest_file"
    
    owner_displayName=$(/usr/libexec/PlistBuddy -c "Print userInfo:InUseOwnerDisplayName" $findmymac_raw_data)
    echo "OwnerDisplayName = $owner_displayName" >> "$findmymac_manfiest_file"
    
    personID=$(/usr/libexec/PlistBuddy -c "Print personID" $findmymac_raw_data)
    echo "personID = $personID" >> "$findmymac_manfiest_file"
    
    hostname=$(/usr/libexec/PlistBuddy -c "Print dataclassProperties:com.apple.Dataclass.DeviceLocator:hostname" $findmymac_raw_data)
    echo "hostname = $hostname" >> "$findmymac_manfiest_file"
fi

exit 0
