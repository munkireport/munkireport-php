#!/bin/bash

# servermetrics controller
CTL="${BASEURL}index.php?/module/timemachine/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/timemachine.py" -o "${MUNKIPATH}preflight.d/timemachine.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/timemachine.py"

	# Set preference to include this file in the preflight check
	setreportpref "timemachine" "${CACHEPATH}timemachine.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/timemachine.py"

	# Signal that we had an error
	ERR=1
fi
