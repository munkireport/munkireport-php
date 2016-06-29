#!/bin/bash

# ds controller
CTL="${BASEURL}index.php?/module/ds/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ds" -o "${MUNKIPATH}preflight.d/ds"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ds"

	# Set preference to include this file in the preflight check
	setreportpref "ds" "${CACHEPATH}ds.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ds"

	# Signal that we had an error
	ERR=1
fi


