#!/bin/bash

# bluetooth controller
CTL="${BASEURL}index.php?/module/bluetooth/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/bluetooth.sh" -o "${MUNKIPATH}preflight.d/bluetooth.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/bluetooth.sh"

	# Set preference to include this file in the preflight check
	defaults write "${PREFPATH}" ReportItems -dict-add bluetooth "${MUNKIPATH}preflight.d/cache/bluetoothinfo.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/bluetooth.sh"

	# Signal that we had an error
	ERR=1
fi


