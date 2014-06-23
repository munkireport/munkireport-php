#!/bin/bash

# network switch controller
CTL="${BASEURL}index.php?/module/network_switch/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/ciscoswitch" -o "${MUNKIPATH}preflight.d/ciscoswitch"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ciscoswitch"

	# Set preference to include this file in the preflight check
	defaults write "${PREFPATH}" ReportItems -dict-add network_switch "${MUNKIPATH}preflight.d/cache/networkswitch.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ciscoswitch"

	# Signal that we had an error
	ERR=1
fi


