#!/bin/bash

# findmymac_manifest controller
CTL="${BASEURL}index.php?/module/findmymac/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/findmymac.sh" -o "${MUNKIPATH}preflight.d/findmymac.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/findmymac.sh"

	# Set preference to include this file in the preflight check
	setreportpref "findmymac" "${CACHEPATH}findmymac.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/findmymac.sh"

	# Signal that we had an error
	ERR=1
fi
