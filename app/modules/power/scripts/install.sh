#!/bin/bash

# power controller
CTL="${BASEURL}index.php?/module/power/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/power.sh" -o "${MUNKIPATH}preflight.d/power.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/power.sh"

	# Set preference to include this file in the preflight check
	setreportpref "power" "${CACHEPATH}powerinfo.plist"
    
    # Delete the older style cached file
    if [[ -f "${MUNKIPATH}preflight.d/cache/powerinfo.txt" ]] ; then
         rm -f "${MUNKIPATH}preflight.d/cache/powerinfo.txt"
    fi

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/power.sh"

	# Signal that we had an error
	ERR=1
fi
