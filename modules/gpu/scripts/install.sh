#!/bin/bash

# gpu controller
CTL="${BASEURL}index.php?/module/gpu/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/gpu.py" -o "${MUNKIPATH}preflight.d/gpu.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/gpu.py"

	# Set preference to include this file in the preflight check
	setreportpref "gpu" "${CACHEPATH}gpuinfo.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/gpu.py"

	# Signal that we had an error
	ERR=1
fi


