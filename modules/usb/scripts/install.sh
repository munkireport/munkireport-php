#!/bin/bash

# usb controller
CTL="${BASEURL}index.php?/module/usb/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/usb.py" -o "${MUNKIPATH}preflight.d/usb.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/usb.py"

	# Set preference to include this file in the preflight check
	setreportpref "usb" "${CACHEPATH}usbinfo.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/usb.py"

	# Signal that we had an error
	ERR=1
fi


