#!/bin/bash

# teamviewer controller
CTL="${BASEURL}index.php?/module/teamviewer/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/teamviewer" -o "${MUNKIPATH}preflight.d/teamviewer"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/teamviewer"

	# Set preference to include this file in the preflight check
	setreportpref "teamviewer" "${CACHEPATH}teamviewer.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/teamviewer"

	# Signal that we had an error
	ERR=1
fi
