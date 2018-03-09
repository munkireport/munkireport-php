#!/bin/bash


# icloud controller
CTL="${BASEURL}index.php?/module/icloud/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/icloud" -o "${MUNKIPATH}preflight.d/icloud"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/icloud"

	# Set preference to include this file in the preflight check
	setreportpref "icloud" "${CACHEPATH}icloud.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/icloud"

	# Signal that we had an error
	ERR=1
fi
