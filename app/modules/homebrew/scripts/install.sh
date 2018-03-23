#!/bin/bash

# homebrew controller
CTL="${BASEURL}index.php?/module/homebrew/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/homebrew.sh" -o "${MUNKIPATH}preflight.d/homebrew.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/homebrew.sh"

	# Touch the cache file to prevent errors
	mkdir -p "${MUNKIPATH}preflight.d/cache"
	touch "${MUNKIPATH}preflight.d/cache/homebrew.json"
    
	# Set preference to include this file in the preflight check
	setreportpref "homebrew" "${CACHEPATH}homebrew.json"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/homebrew.sh"

	# Signal that we had an error
	ERR=1
fi


