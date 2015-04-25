#!/bin/bash

# path to controller
DR_CTL="${BASEURL}index.php?/module/ard/"
ARDPREF=/Library/Preferences/com.apple.RemoteDesktop
SCRIPTNAME=init_ard

# Get the script in the proper directory
${CURL} "${DR_CTL}get_script/${SCRIPTNAME}" -o "${MUNKIPATH}preflight.d/${SCRIPTNAME}"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/${SCRIPTNAME}"

	# Set preference to include this file in the preflight check
	setreportpref "ard_model" "${ARDPREF}.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/${SCRIPTNAME}"
	ERR=1
fi
