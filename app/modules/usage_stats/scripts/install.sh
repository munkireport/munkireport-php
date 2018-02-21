#!/bin/bash

# usage_stats controller
CTL="${BASEURL}index.php?/module/usage_stats/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/usage_stats" -o "${MUNKIPATH}preflight.d/usage_stats"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/usage_stats"

	# Set preference to include this file in the preflight check
	setreportpref "usage_stats" "${CACHEPATH}usage_stats.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/usage_stats"

	# Signal that we had an error
	ERR=1
fi


