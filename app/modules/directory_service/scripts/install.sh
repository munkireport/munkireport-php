#!/bin/bash

# directory service controller
CTL="${BASEURL}index.php?/module/directory_service/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/directoryservice.sh" -o "${MUNKIPATH}preflight.d/directoryservice.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/directoryservice.sh"

	# Set preference to include this file in the preflight check
	defaults write "${PREFPATH}" ReportItems -dict-add directory_service_model "${MUNKIPATH}preflight.d/cache/directoryservice.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/directoryservice.sh"

	# Signal that we had an error
	ERR=1
fi


