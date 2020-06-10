#!/bin/bash

# MODULE controller
CTL="${BASEURL}index.php?/module/MODULE/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/MODULE.sh" -o "${MUNKIPATH}preflight.d/MODULE.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/MODULE.sh"

	# Set preference to include this file in the preflight check
	setreportpref "MODULE" "${CACHEPATH}MODULE.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/MODULE.sh"

	# Signal that we had an error
	ERR=1
fi
