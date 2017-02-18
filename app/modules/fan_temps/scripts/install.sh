#!/bin/bash

# Check if caching is 10.9 or higher, only install if it is
OSVERSION=$(/usr/bin/sw_vers -productVersion | /usr/bin/cut -d . -f 2)
if [[ ${OSVERSION} -lt 9 ]]; then

	echo "Error: Fans and Temperatures modules requires 10.9 or higher!"
	
	# Signal that we had an error
	ERR=1
else

# fan_temps_controller
NW_CTL="${BASEURL}index.php?/module/fan_temps/"


# Get the script in the proper directory
${CURL} -s "${NW_CTL}get_script/fan_temps.sh" -o "${MUNKIPATH}preflight.d/fan_temps.sh"
${CURL} -s "${NW_CTL}get_script/smckit.zip" -o "${MUNKIPATH}smckit.zip"


if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/fan_temps.sh"
	rm -f "${MUNKIPATH}smckit.zip"
	exit 1
else
	# Unzip the executable
	unzip  -oqq "${MUNKIPATH}smckit.zip" -d "${MUNKIPATH}"

	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/fan_temps.sh"
	chmod a+x "${MUNKIPATH}smckit"
    
	# Clean up
	rm -f "${MUNKIPATH}smckit.zip"

	# Set preference to include this file in the preflight check
	setreportpref "fan_temps" "${CACHEPATH}fan_temps.xml"
fi
fi