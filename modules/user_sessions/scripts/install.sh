#!/bin/bash

# user_sessions controller
CTL="${BASEURL}index.php?/module/user_sessions/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/user_sessions.py" -o "${MUNKIPATH}preflight.d/user_sessions.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/user_sessions.py"

	# Set preference to include this file in the preflight check
	setreportpref "user_sessions" "${CACHEPATH}user_sessions.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/user_sessions.py"

	# Signal that we had an error
	ERR=1
fi


