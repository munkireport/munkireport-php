#!/bin/bash

# Jamf controller
CTL="${BASEURL}index.php?/module/jamf/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/jamf" -o "${MUNKIPATH}preflight.d/jamf"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/jamf"

	# Set preference to include this file in the preflight check
	setreportpref "jamf" "${CACHEPATH}jamf.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/jamf"

	# Signal that we had an error
	ERR=1
fi


