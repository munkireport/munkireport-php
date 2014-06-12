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

# Bluetooth status. Returns '0' for off and '1' for on
Power=`defaults read /Library/Preferences/com.apple.Bluetooth.plist ControllerPowerState`
if [ $Power = 0 ]; then
	status="Status = Bluetooth is off"
		else
	status="Status = Bluetooth is on"
fi

KeyboardPercent=`ioreg -c AppleBluetoothHIDKeyboard | grep BatteryPercent | sed 's/[a-z,A-Z, ,|,",=]//g' | tail -1 | awk '{print $1}'`
if [ "${KeyboardPercent}" = "" ]; then
	keyboard="Keyboard = Disconnected"
		else 
	keyboard="Keyboard = $KeyboardPercent% battery life remaining"
fi

MousePercent=`ioreg -c BNBMouseDevice | grep BatteryPercent | sed 's/[a-z,A-Z, ,|,",=]//g' | tail -1 | awk '{print $1}'`
if [ "${MousePercent}" = "" ]; then
	mouse="Mouse = Disconnected"
		else 
	mouse="Mouse = $MousePercent% battery life remaining"
fi

TrackpadPercent=`ioreg -c BNBTrackpadDevice | grep BatteryPercent | sed 's/[a-z,A-Z, ,|,",=]//g' | tail -1 | awk '{print $1}'`
if [ "${TrackpadPercent}" = "" ]; then
	trackpad="Trackpad = Disconnected"
		else 
	trackpad="Trackpad = $TrackpadPercent% battery life remaining"
fi


echo $status '\n'$keyboard '\n'$mouse '\n'$trackpad > "$bluetoothfile"