#!/bin/bash


# launchdaemons controller
CTL="${BASEURL}index.php?/module/launchdaemons/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/launchdaemons" -o "${MUNKIPATH}preflight.d/launchdaemons"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/launchdaemons"

	# Set preference to include this file in the preflight check
	setreportpref "launchdaemons" "${CACHEPATH}launchdaemons.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/launchdaemons"

	# Signal that we had an error
	ERR=1
fi
