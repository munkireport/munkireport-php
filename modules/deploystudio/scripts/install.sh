#!/bin/bash

# deploystudio controller
CTL="${BASEURL}index.php?/module/deploystudio/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/deploystudio" -o "${MUNKIPATH}preflight.d/deploystudio"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/deploystudio"

	# Set preference to include this file in the preflight check
	setreportpref "deploystudio" "${CACHEPATH}deploystudio.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/deploystudio"

	# Signal that we had an error
	ERR=1
fi


