#!/bin/bash


# ios_devices controller
CTL="${BASEURL}index.php?/module/ios_devices/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ios_devices" -o "${MUNKIPATH}preflight.d/ios_devices"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ios_devices"

	# Set preference to include this file in the preflight check
	setreportpref "ios_devices" "${CACHEPATH}ios_devices.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ios_devices"

	# Signal that we had an error
	ERR=1
fi
