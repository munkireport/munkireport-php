#!/bin/bash

# Create munkireportlib directory
mkdir -p ${MUNKIPATH}munkireportlib

# servermetrics controller
CTL="${BASEURL}index.php?/module/servermetrics/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/servermetrics.py" -o "${MUNKIPATH}preflight.d/servermetrics.py" && 
"${CURL[@]}" "${CTL}get_script/ccl_asldb.py" -o "${MUNKIPATH}munkireportlib/ccl_asldb.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/servermetrics.py"

	# Set preference to include this file in the preflight check
	setreportpref "servermetrics" "${CACHEPATH}servermetrics.json"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/servermetrics.py"

	# Signal that we had an error
	ERR=1
fi
