#!/bin/bash

# printer controller
CTL="${BASEURL}index.php?/module/printer/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/printer.py" -o "${MUNKIPATH}preflight.d/printer.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/printer.py"

	# Set preference to include this file in the preflight check
	setreportpref "printer" "${CACHEPATH}printer.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/printer.py"

	# Signal that we had an error
	ERR=1
fi

