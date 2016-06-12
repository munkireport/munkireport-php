#!/bin/bash

# managedinstalls controller
CTL="${BASEURL}index.php?/module/managedinstalls/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/managedinstalls.py" -o "${MUNKIPATH}preflight.d/managedinstalls.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/managedinstalls.py"

	# Set preference to include this file in the preflight check
	setreportpref "managedinstalls" "${CACHEPATH}managedinstalls.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/managedinstalls.py"

	# Signal that we had an error
	ERR=1
fi
