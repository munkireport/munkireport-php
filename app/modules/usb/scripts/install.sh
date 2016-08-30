#!/bin/bash

# kb_mouse controller
CTL="${BASEURL}index.php?/module/usb/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/usb.sh" -o "${MUNKIPATH}preflight.d/usb.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/usb.sh"

	# Set preference to include this file in the preflight check
	setreportpref "usb" "${CACHEPATH}usb_info.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/usb.sh"

	# Signal that we had an error
	ERR=1
fi


