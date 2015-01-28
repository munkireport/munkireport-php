#!/bin/bash

# inventory controller
DR_CTL="${BASEURL}index.php?/module/inventory/"

# Get the scripts in the proper directories
"${CURL[@]}" "${DR_CTL}get_script/inventory_add_plugins" -o "${MUNKIPATH}postflight.d/inventory_add_plugins.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}postflight.d/inventory_add_plugins.py"

	# Set preference to include this file in the preflight check
	defaults write "${PREFPATH}" ReportItems -dict-add inventory "/Library/Managed Installs/ApplicationInventory.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}postflight.d/inventory_add_plugins.py"
	ERR=1
fi

