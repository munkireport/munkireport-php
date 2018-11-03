#!/bin/bash

# profile controller
CTL="${BASEURL}index.php?/module/profile/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/profile.py" -o "${MUNKIPATH}preflight.d/profile.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/profile.py"

	# Set preference to include this file in the preflight check
	setreportpref "profile" "${CACHEPATH}profile.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/profile.py"

	# Signal that we had an error
	ERR=1
fi
