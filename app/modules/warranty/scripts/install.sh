#!/bin/bash

# warranty controller
CTL="${BASEURL}index.php?/module/warranty/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/warranty" -o "${MUNKIPATH}preflight.d/warranty"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/warranty"

	# Set preference to include this file in the preflight check
	setreportpref "warranty" "${CACHEPATH}warranty.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/warranty"

	# Signal that we had an error
	ERR=1
fi


