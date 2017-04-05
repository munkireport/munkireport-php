#!/bin/bash

MODULE_NAME="caching"
MODULESCRIPT="caching"
MODULE_CACHE_FILE="caching.txt"

CTL="${BASEURL}index.php?/module/${MODULE_NAME}/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/${MODULESCRIPT}" -o "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/${MODULESCRIPT}"
	touch "${MUNKIPATH}preflight.d/cache/${MODULE_CACHE_FILE}"

	# Set preference to include this file in the preflight check
	setreportpref $MODULE_NAME "${CACHEPATH}${MODULE_CACHE_FILE}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

	# Signal that we had an error
	ERR=1
fi
