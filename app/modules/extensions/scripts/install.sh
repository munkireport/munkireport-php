#!/bin/bash

# extensions controller
CTL="${BASEURL}index.php?/module/extensions/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/extensions.py" -o "${MUNKIPATH}preflight.d/extensions.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/extensions.py"

	# Set preference to include this file in the preflight check
	setreportpref "extensions" "${CACHEPATH}extensions.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/extensions.py"

	# Signal that we had an error
	ERR=1
fi


