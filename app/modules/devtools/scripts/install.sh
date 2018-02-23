#!/bin/bash

# devtools controller
CTL="${BASEURL}index.php?/module/devtools/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/devtools.py" -o "${MUNKIPATH}preflight.d/devtools.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/devtools.py"
    
	# Touch the cache file to prevent errors
	mkdir -p "${MUNKIPATH}preflight.d/cache"
	touch "${MUNKIPATH}preflight.d/cache/homebrew_info.json"

	# Set preference to include this file in the preflight check
	setreportpref "devtools" "${CACHEPATH}devtools.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/devtools.py"

	# Signal that we had an error
	ERR=1
fi


