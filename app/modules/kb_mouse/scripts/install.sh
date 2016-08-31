#!/bin/bash

# kb_mouse controller
CTL="${BASEURL}index.php?/module/kb_mouse/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/kb_mouse.sh" -o "${MUNKIPATH}preflight.d/kb_mouse.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/kb_mouse.sh"

	# Set preference to include this file in the preflight check
	setreportpref "kb_mouse" "${CACHEPATH}kb_mouse_info.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/kb_mouse.sh"

	# Signal that we had an error
	ERR=1
fi


