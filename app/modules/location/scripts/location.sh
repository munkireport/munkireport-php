#!/bin/sh

# https://groups.google.com/forum/#!searchin/munkireport/map/munkireport/6uMTh9g5xDs/RcH_gx0fl_sJ

# Credit to bollman https://jamfnation.jamfsoftware.com/discussion.html?id=12300

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"

locationfile="$DIR/cache/location.txt"

echo "Getting device location"

INTERFACE=$(networksetup -listallhardwareports | grep -A1 Wi-Fi | tail -1 | awk '{print $2}')
STATUS=$(networksetup -getairportpower $INTERFACE | awk '{print $4}')
if [ $STATUS = "Off" ] ; then
	sleep 5
	networksetup -setairportpower $INTERFACE on
fi

/System/Library/PrivateFrameworks/Apple80211.framework/Versions/A/Resources/airport -s | tail -n +2 | awk '{print substr($0, 34, 17)"$"substr($0, 52, 4)"$"substr($0, 1, 32)}' | sort -t $ -k2,2rn | head -12 > /tmp/gl_ssids.txt

if [ $STATUS = "Off" ] ; then
	networksetup -setairportpower $INTERFACE off
fi

OLD_IFS=$IFS
IFS="$"
URL="https://maps.googleapis.com/maps/api/browserlocation/json?browser=firefox"
exec 5</tmp/gl_ssids.txt
while read -u 5 MAC SS SSID
do
	SSID=`echo $SSID | sed "s/^ *//g" | sed "s/ *$//g" | sed "s/ /%20/g"`
	MAC=`echo $MAC | sed "s/^ *//g" | sed "s/ *$//g"`
	SS=`echo $SS | sed "s/^ *//g" | sed "s/ *$//g"`
	URL+="&wifi=mac:$MAC&ssid:$SSID&ss:$SS"
done
IFS=$OLD_IFS

curl -s -A "Mozilla" "$URL" > /tmp/gl_coordinates.txt
latitude=`cat /tmp/gl_coordinates.txt | grep \"lat\" | awk '{print $3}' | tr -d ","`
longitude=`cat /tmp/gl_coordinates.txt | grep \"lng\" | awk '{print $3}' | tr -d ","`
accuracy=`cat /tmp/gl_coordinates.txt | grep \"accuracy\" | awk '{print $3}' | tr -d ","`

curl -s -A "Mozilla" "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude" > /tmp/gl_address.txt
address=`cat /tmp/gl_address.txt | grep "formatted_address" | head -1 | awk '{$1=$2=""; print $0}' | sed "s/,$//g" | tr -d \" | sed "s/^ *//g"`

echo '\n'latitude = $latitude '\n'longitude = $longitude '\n'accuracy = $accuracy '\n'address = $address> "$locationfile"

# rm /tmp/gl_ssids.txt /tmp/gl_coordinates.txt /tmp/gl_address.txt

exit 0
