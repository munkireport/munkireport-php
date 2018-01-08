#!/bin/bash

# smart_stats_controller
CTL="${BASEURL}index.php?/module/smart_stats/"

# Get the script in the proper directory

"${CURL[@]}" "${CTL}get_script/smart_stats" -o "${MUNKIPATH}preflight.d/smart_stats"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f ${MUNKIPATH}preflight.d/smart_stats
	exit 1
else
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/smart_stats"
    
	# Delete the older smart_stats.sh file
	if [[ -f "${MUNKIPATH}preflight.d/smart_stats.sh" ]] ; then
	     rm -f "${MUNKIPATH}preflight.d/smart_stats.sh"
	fi

	# Set preference to include this file in the preflight check
	setreportpref "smart_stats" "${CACHEPATH}smart_stats.plist"
fi