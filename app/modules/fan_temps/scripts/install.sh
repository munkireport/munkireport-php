#!/bin/bash

# fan_temps_controller
NW_CTL="${BASEURL}index.php?/module/fan_temps/"

# Get the script in the proper directory
"${CURL[@]}"  -s "${NW_CTL}get_script/fan_temps" -o "${MUNKIPATH}preflight.d/fan_temps"

# Only download smc.zip if smc doesn't exist
if [ ! -f "${MUNKIPATH}smc" ]; then
    "${CURL[@]}"  -s "${NW_CTL}get_script/smc.zip" -o "${MUNKIPATH}smc.zip"
fi

if [ "${?}" != 0 ]; then
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/fan_temps"
	rm -f "${MUNKIPATH}smc.zip"
	exit 1
else

    # Delete smckit
    if [ -f "${MUNKIPATH}smckit" ]; then
        rm -f "${MUNKIPATH}smckit"
    fi

    # Delete fan_temps.sh
    if [ -f "${MUNKIPATH}preflight.d/fan_temps.sh" ]; then
        rm -f "${MUNKIPATH}preflight.d/fan_temps.sh"
    fi

	# Unzip the executable only if it exists
	if [ -f "${MUNKIPATH}smc.zip" ]; then
	     unzip  -oqq "${MUNKIPATH}smc.zip" -d "${MUNKIPATH}"
	fi

	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/fan_temps"
	chmod a+x "${MUNKIPATH}smc"
    
	# Clean up smc.zip only if it exists
	if [ -f "${MUNKIPATH}smc.zip" ]; then
	     rm -f "${MUNKIPATH}smc.zip"
	fi
    
	# Set preference to include this file in the preflight check
	setreportpref "fan_temps" "${CACHEPATH}fan_temps.plist"
fi