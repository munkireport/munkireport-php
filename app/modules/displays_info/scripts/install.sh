#!/bin/bash

# Displays_info controller
CTL="${BASEURL}index.php?/module/displays_info/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/displays.py" -o "${MUNKIPATH}preflight.d/displays.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/displays.py"

	# Delete the older style cached file
	if [[ -f "${MUNKIPATH}preflight.d/cache/displays.txt" ]] ; then
		rm -f "${MUNKIPATH}preflight.d/cache/displays.txt"
	fi

	# Set preference to include this file in the preflight check
	setreportpref "displays_info" "${CACHEPATH}displays.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/displays.py"

	# Signal that we had an error
	ERR=1
fi
