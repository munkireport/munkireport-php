#!/bin/bash

# localadmin controller
CTL="${BASEURL}index.php?/module/localadmin/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/localadmin" -o "${MUNKIPATH}preflight.d/localadmin"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/localadmin"

	# Set preference to include this file in the preflight check
	setreportpref "localadmin" "${CACHEPATH}localadmins.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/localadmin"

	# Signal that we had an error
	ERR=1
fi


