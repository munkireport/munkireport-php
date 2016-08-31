#!/bin/sh

# kb_mouse.sh

# Keyboard, mouse and trackpad status checks

###
# functions

function check_KBnMouse {

	# this checks for attached mouse, keyboard and trackpad
	#
	# utilizes ioreg
	#
	# works in OS X 10.6-10.11
	#
	# examples
	#
	# Apple Internal Keyboard / Trackpad
	#
	# Apple Optical USB Mouse
	# Logitech USB Mouse
	# USB-PS/2 Optical Mouse
	# BNBMouseDevice (Apple BT Magic Mouse)

	# Apple Keyboard
	# Apple Extended USB Keyboard
	# USB Keyboard
	# Logitech USB Keyboard


	# for external keyboard(s)
	extKeybName=$(ioreg -c IOUSB | egrep -i -w 'keyboard' | egrep -i -v 'boot|hid|hub|internal' | sort -u | sed '/^$/d' | cut -d'@' -f1 | awk -F '-o ' '{print $NF}' | awk -F '  <class' '{print $1}' | head -1)

	if [ ! "" == "${extKeybName}" ]
	then
		echo "EXT_KEYBOARD_STATUS:1" >> "${kb_mouse_file}" 2>/dev/null
		echo "EXT_KEYBOARD_NAME:${extKeybName}" >> "${kb_mouse_file}" 2>/dev/null
	else
		echo "EXT_KEYBOARD_STATUS:0" >> "${kb_mouse_file}" 2>/dev/null
		echo "EXT_KEYBOARD_NAME:" >> "${kb_mouse_file}" 2>/dev/null
	fi

	# for external mouse/mice with Apple Bluetooth mouse too
	extMouseName=$(ioreg -c IOUSB | egrep -i 'mouse' | egrep -i -v 'applehid|hub|internal' | sed '/^$/d' | cut -d'@' -f1 | awk -F '-o ' '{print $NF}' | awk -F '  <class' '{print $1}' | head -1)

	if [ ! "" == "${extMouseName}" ]
	then
		echo "EXT_MOUSE_STATUS:1" >> "${kb_mouse_file}" 2>/dev/null
		echo "EXT_MOUSE_NAME:${extMouseName}" >> "${kb_mouse_file}" 2>/dev/null
	else
		echo "EXT_MOUSE_STATUS:0" >> "${kb_mouse_file}" 2>/dev/null
		echo "EXT_MOUSE_NAME:" >> "${kb_mouse_file}" 2>/dev/null
	fi

	# internal laptop keyboard (plus trackpad)
	intTrackPadKeysName=$(ioreg -c IOUSB | egrep -i 'trackpad' | egrep -v 'HID|Boot' | sort -u | head -1 | sed '/^$/d' | cut -d'@' -f1 | awk -F '-o ' '{print $NF}' | awk -F '  <class' '{print $1}')
	if [ ! "" == "${intTrackPadKeysName}" ]
	then
		echo "INT_TRPAD_STATUS:1" >> "${kb_mouse_file}" 2>/dev/null
		echo "INT_TRPAD_NAME:${intTrackPadKeysName}" >> "${kb_mouse_file}" 2>/dev/null
	else
		echo "INT_TRPAD_STATUS:0" >> "${kb_mouse_file}" 2>/dev/null
		echo "INT_TRPAD_NAME:" >> "${kb_mouse_file}" 2>/dev/null
	fi

}

###
# actions

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"

kb_mouse_file="$DIR/cache/kb_mouse_info.txt"

echo $(date '+STATUS_CHECKED_TIMESTAMP:%F %T') > "$DIR/cache/kb_mouse_info.txt"

check_KBnMouse

exit 0
