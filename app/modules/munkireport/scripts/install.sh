#!/bin/bash

# Remove previous script and plist from preflight.d
rm -f "${MUNKIPATH}preflight.d/munkireport.py"
rm -f "${MUNKIPATH}preflight.d/cache/munkireport.plist"

# managedinstalls controller
CTL="${BASEURL}index.php?/module/munkireport/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/munkireport.py" -o "${MUNKIPATH}postflight.d/munkireport.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}postflight.d/munkireport.py"

	# Set preference to include this file in the preflight check
	setreportpref "munkireport" "${POSTFLIGHT_CACHEPATH}munkireport.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}postflight.d/munkireport.py"

	# Signal that we had an error
	ERR=1
fi
