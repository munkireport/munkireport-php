#!/bin/bash

# smart_stats_controller
CTL="${BASEURL}index.php?/module/smart_stats/"

# Get the script in the proper directory

"${CURL[@]}" "${CTL}get_script/smart_stats.sh" -o "${MUNKIPATH}preflight.d/smart_stats.sh"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f ${MUNKIPATH}preflight.d/smart_stats.sh
	exit 1
else
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/smart_stats.sh"
    
	# Update smartctl database
	if [[ -f /usr/local/sbin/update-smart-drivedb ]]; then
	     /usr/local/sbin/update-smart-drivedb > /dev/null 2>&1
	elif [[ -f /usr/local/bin/update-smart-drivedb ]]; then
	     /usr/local/bin/update-smart-drivedb > /dev/null 2>&1
	fi

	# Set preference to include this file in the preflight check
	setreportpref "smart_stats" "${CACHEPATH}smart_stats.xml"
fi