#!/bin/bash

# Remove previous script and plist from preflight.d
rm -f "${MUNKIPATH}preflight.d/managedinstalls.py"
rm -f "${MUNKIPATH}preflight.d/cache/managedinstalls.plist"

# managedinstalls controller
CTL="${BASEURL}index.php?/module/managedinstalls/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/managedinstalls.py" -o "${MUNKIPATH}postflight.d/managedinstalls.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}postflight.d/managedinstalls.py"

	# Set preference to include this file in the preflight check
	setreportpref "managedinstalls" "${MUNKIPATH}postflight.d/cache/managedinstalls.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}postflight.d/managedinstalls.py"

	# Signal that we had an error
	ERR=1
fi
