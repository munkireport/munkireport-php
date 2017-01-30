#!/bin/bash

MODULE_NAME="appusage"
MODULE_CACHE_FILE="appusage.csv"

CTL="${BASEURL}index.php?/module/${MODULE_NAME}/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/appusage" -o "${MUNKIPATH}preflight.d/appusage"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/appusage"
	mkdir -p "${CACHEPATH}"
	touch "${CACHEPATH}${MODULE_CACHE_FILE}"

	# Set preference to include this file in the preflight check
	setreportpref $MODULE_NAME "${CACHEPATH}${MODULE_CACHE_FILE}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/appusage"

	# Signal that we had an error
	ERR=1
fi
