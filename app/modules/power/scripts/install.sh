#!/bin/bash

# power controller
CTL="${BASEURL}index.php?/module/power/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/power" -o "${MUNKIPATH}preflight.d/power"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/power"

	# Set preference to include this file in the preflight check
	setreportpref "power" "${CACHEPATH}powerinfo.plist"
    
    # Delete the older style cached file
    if [[ -f "${MUNKIPATH}preflight.d/cache/powerinfo.txt" ]] ; then
         rm -f "${MUNKIPATH}preflight.d/cache/powerinfo.txt"
    fi
    
    # Delete the older power.sh file
    if [[ -f "${MUNKIPATH}preflight.d/power.sh" ]] ; then
         rm -f "${MUNKIPATH}preflight.d/power.sh"
    fi

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/power"

	# Signal that we had an error
	ERR=1
fi
