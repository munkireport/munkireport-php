#!/bin/sh

# Battery status check for Apple Wireless Keyboard, Mouse, and Trackpad
# Based on http://www.macosxtips.co.uk/geeklets/system/battery-status-for-apple-wireless-keyboard-mouse-and-trackpad/

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"

bluetoothfile="$DIR/cache/bluetoothinfo.txt"

# echo "Getting Bluetooth device status"

# Bluetooth status.
status="Status = 0"
system_profiler SPBluetoothDataType | grep -q 'Bluetooth Power: On' && status="Status = 1"

KeyboardPercent=`ioreg -c AppleBluetoothHIDKeyboard | grep BatteryPercent | sed 's/[a-z,A-Z, ,|,",=]//g' | tail -1 | awk '{print $1}'`
if [ "${KeyboardPercent}" = "" ]; then
	keyboard="Keyboard = -1"
else
	keyboard="Keyboard = $KeyboardPercent"
fi

MousePercent=`ioreg -c BNBMouseDevice | grep BatteryPercent | sed 's/[a-z,A-Z, ,|,",=]//g' | tail -1 | awk '{print $1}'`
if [ "${MousePercent}" = "" ]; then
	mouse="Mouse = -1"
else
	mouse="Mouse = $MousePercent"
fi

TrackpadPercent=`ioreg -c BNBTrackpadDevice | grep BatteryPercent | sed 's/[a-z,A-Z, ,|,",=]//g' | tail -1 | awk '{print $1}'`
if [ "${TrackpadPercent}" = "" ]; then
	trackpad="Trackpad = -1"
else
	trackpad="Trackpad = $TrackpadPercent"
fi


echo $status '\n'$keyboard '\n'$mouse '\n'$trackpad > "$bluetoothfile"
