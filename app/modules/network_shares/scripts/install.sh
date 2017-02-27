#!/bin/bash

# network_shares controller
CTL="${BASEURL}index.php?/module/network_shares/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/network_shares.py" -o "${MUNKIPATH}preflight.d/network_shares.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/network_shares.py"

	# Set preference to include this file in the preflight check
	setreportpref "network_shares" "${CACHEPATH}network_shares.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/network_shares.py"

	# Signal that we had an error
	ERR=1
fi


