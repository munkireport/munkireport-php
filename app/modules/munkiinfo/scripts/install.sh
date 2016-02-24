#!/bin/bash

MODULE_NAME="munkiinfo"
MODULESCRIPT="munkiinfo.py"
MODULE_CACHE_FILE="munkiinfo.plist"

CTL="${BASEURL}index.php?/module/${MODULE_NAME}/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/${MODULESCRIPT}" -o "${MUNKIPATH}postflight.d/${MODULESCRIPT}"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}postflight.d/${MODULESCRIPT}"

	# Set preference to include this file in the postflight check
	setreportpref $MODULE_NAME "${CACHEPATH}${MODULE_CACHE_FILE}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}postflight.d/${MODULESCRIPT}"

	# Signal that we had an error
	ERR=1
fi
