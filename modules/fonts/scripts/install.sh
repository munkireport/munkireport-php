#!/bin/bash

# fonts controller
CTL="${BASEURL}index.php?/module/fonts/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/fonts.py" -o "${MUNKIPATH}preflight.d/fonts.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/fonts.py"

	# Set preference to include this file in the preflight check
	setreportpref "fonts" "${CACHEPATH}fonts.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/fonts.py"

	# Signal that we had an error
	ERR=1
fi


