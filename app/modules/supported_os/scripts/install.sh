#!/bin/bash

# supported_os controller
CTL="${BASEURL}index.php?/module/supported_os/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/supported_os" -o "${MUNKIPATH}preflight.d/supported_os"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/supported_os"

	# Set preference to include this file in the preflight check
	setreportpref "supported_os" "${CACHEPATH}supported_os.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/supported_os"

	# Signal that we had an error
	ERR=1
fi


