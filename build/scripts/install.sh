#!/bin/bash

# zappa controller
CTL="${BASEURL}index.php?/module/zappa/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/zappa.sh" -o "${MUNKIPATH}preflight.d/zappa.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/zappa.sh"

	# Set preference to include this file in the preflight check
	setreportpref "zappa" "${CACHEPATH}zappa.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/zappa.sh"

	# Signal that we had an error
	ERR=1
fi
