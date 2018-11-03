#!/bin/bash

# bluetooth controller
CTL="${BASEURL}index.php?/module/bluetooth/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/bluetooth.py" -o "${MUNKIPATH}preflight.d/bluetooth.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/bluetooth.py"

	# Set preference to include this file in the preflight check
	setreportpref "bluetooth" "${CACHEPATH}bluetoothinfo.plist"

	# Remove old shell bluetooth script if it exists
	rm -f "${MUNKIPATH}preflight.d/bluetooth.sh"
else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/bluetooth.{sh,py}"

	# Signal that we had an error
	ERR=1
fi


