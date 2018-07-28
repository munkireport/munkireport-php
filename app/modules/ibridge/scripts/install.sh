#!/bin/bash

# ibridge controller
CTL="${BASEURL}index.php?/module/ibridge/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ibridge.py" -o "${MUNKIPATH}preflight.d/ibridge.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ibridge.py"
    
	# Touch the cache file to prevent errors
	mkdir -p "${MUNKIPATH}preflight.d/cache"
	touch "${MUNKIPATH}preflight.d/cache/ibridge.plist"

	# Set preference to include this file in the preflight check
	setreportpref "ibridge" "${CACHEPATH}ibridge.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ibridge.py"

	# Signal that we had an error
	ERR=1
fi


