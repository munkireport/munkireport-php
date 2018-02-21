#!/bin/bash

# memory controller
CTL="${BASEURL}index.php?/module/memory/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/memory.py" -o "${MUNKIPATH}preflight.d/memory.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/memory.py"

	# Set preference to include this file in the preflight check
	setreportpref "memory" "${CACHEPATH}memoryinfo.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/memory.py"

	# Signal that we had an error
	ERR=1
fi


