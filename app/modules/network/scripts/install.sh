#!/bin/bash

# filevault_status_controller
NW_CTL="${BASEURL}index.php?/module/network/"

# remove the previous networkinfo.sh if installed
rm -f "${MUNKIPATH}preflight.d/networkinfo.sh"

# Get the script in the proper directory
"${CURL[@]}" "${NW_CTL}get_script/networkinfo.py" -o "${MUNKIPATH}preflight.d/networkinfo.py"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f ${MUNKIPATH}preflight.d/networkinfo.py
	exit 1
fi

# Make executable
chmod a+x "${MUNKIPATH}preflight.d/networkinfo.py"

# Set preference to include this file in the preflight check
setreportpref "network" "${CACHEPATH}networkinfo.txt"
