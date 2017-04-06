#!/bin/bash
MODULE_NAME="backup2go"

# backup2go controller
CTL="${BASEURL}index.php?/module/backup2go/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/backup2go.sh" -o "${MUNKIPATH}preflight.d/backup2go.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/backup2go.sh"

	# Set preference to include this file in the preflight check
	setreportpref "backup2go" "${CACHEPATH}backup2go.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/backup2go.sh"

	# Signal that we had an error
	ERR=1
fi
