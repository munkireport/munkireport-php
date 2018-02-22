#!/bin/bash

# homebrew_info controller
CTL="${BASEURL}index.php?/module/homebrew_info/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/homebrew_info.sh" -o "${MUNKIPATH}preflight.d/homebrew_info.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/homebrew_info.sh"

	# Set preference to include this file in the preflight check
	setreportpref "homebrew_info" "${CACHEPATH}homebrew_info.json"

	# Touch the cache file to prevent errors
	touch "${MUNKIPATH}preflight.d/cache/${MODULE_CACHE_FILE}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/homebrew_info.sh"

	# Signal that we had an error
	ERR=1
fi


