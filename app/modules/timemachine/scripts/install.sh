#!/bin/bash

# time machine controller
CTL="${BASEURL}index.php?/module/timemachine/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/timemachine" -o "${MUNKIPATH}preflight.d/timemachine"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/timemachine"

	# Set preference to include this file in the preflight check
	setreportpref "timemachine" "${CACHEPATH}timemachine.plist"

	# Delete the older style txt cache file
	if [[ -f "${MUNKIPATH}preflight.d/cache/timemachine.txt" ]] ; then
	     rm -f "${MUNKIPATH}preflight.d/cache/timemachine.txt"
	fi

	# Delete the older timemachine.sh
	if [[ -f "${MUNKIPATH}preflight.d/timemachine.sh" ]] ; then
	     rm -f "${MUNKIPATH}preflight.d/timemachine.sh"
	fi

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/timemachine"

	# Signal that we had an error
	ERR=1
fi
