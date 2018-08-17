#!/bin/bash

# laps controller
CTL="${BASEURL}index.php?/module/laps/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/macOSLAPS" -o "${MUNKIPATH}preflight.d/macOSLAPS"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/macOSLAPS"

	# Set preference to include this file in the preflight check
	setreportpref "laps" "/var/root/.mrlaps"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/macOSLAPS"

	# Signal that we had an error
	ERR=1
fi