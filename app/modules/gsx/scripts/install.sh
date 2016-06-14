#!/bin/bash

# gsx controller
CTL="${BASEURL}index.php?/module/gsx/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/gsx" -o "${MUNKIPATH}preflight.d/gsx"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/gsx"

	# Set preference to include this file in the preflight check
	setreportpref "gsx" "${CACHEPATH}gsx.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/gsx"

	# Signal that we had an error
	ERR=1
fi


