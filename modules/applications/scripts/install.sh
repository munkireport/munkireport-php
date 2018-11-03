#!/bin/bash

# applications controller
CTL="${BASEURL}index.php?/module/applications/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/applications.py" -o "${MUNKIPATH}preflight.d/applications.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/applications.py"

	# Set preference to include this file in the preflight check
	setreportpref "applications" "${CACHEPATH}applications.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/applications.py"

	# Signal that we had an error
	ERR=1
fi


