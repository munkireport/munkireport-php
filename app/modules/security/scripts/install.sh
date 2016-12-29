#!/bin/bash

MODULE_NAME="security"
MODULESCRIPT="security.py"
MODULE_CACHE_FILE="security.plist"

CTL="${BASEURL}index.php?/module/${MODULE_NAME}/"

# Remove old versions of the script and cache file
if [ -f "${MUNKIPATH}preflight.d/security.sh" ]; then
	rm "${MUNKIPATH}preflight.d/security.sh"
fi

if [ -f "${MUNKIPATH}preflight.d/cache/security.txt" ]; then
	rm "${MUNKIPATH}preflight.d/cache/security.txt"
fi


# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/${MODULESCRIPT}" -o "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

	# Set preference to include this file in the preflight check
	setreportpref $MODULE_NAME "${CACHEPATH}${MODULE_CACHE_FILE}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

	# Signal that we had an error
	ERR=1
fi
