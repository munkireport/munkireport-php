#!/bin/bash

# Path to location directory and pref
LOCATION_DIR="/Library/Application Support/pinpoint"
LOCATION_PREF="${LOCATION_DIR}/location.plist"
MODULESCRIPT="init_location"
MODULE_NAME="location"

# map controller
CTL="${BASEURL}index.php?/module/${MODULE_NAME}/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/${MODULESCRIPT}" -o "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

	# Set preference to include this file in the preflight check
	setreportpref "${MODULE_NAME}" "${LOCATION_PREF}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

	# Signal that we had an error
	ERR=1
fi

# Remove old testing script and cache if they exist
files=( "${MUNKIPATH}preflight.d/location.py" "${MUNKIPATH}preflight.d/cache/location.plist" )
for i in "${files[@]}"
do
	/bin/rm -f $i
done




