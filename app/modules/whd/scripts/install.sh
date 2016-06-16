#!/bin/bash

# whd controller
CTL="${BASEURL}index.php?/module/whd/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/whd" -o "${MUNKIPATH}preflight.d/whd"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/whd"

	# Set preference to include this file in the preflight check
	setreportpref "whd" "${CACHEPATH}whd.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/whd"

	# Signal that we had an error
	ERR=1
fi


