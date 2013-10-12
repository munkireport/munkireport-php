#!/bin/bash

echo 'Install disk report'

# disk_report controller
DR_CTL="${BASEURL}/index.php?/module/disk_report/"

# Get the scripts in the proper directories
curl --fail --silent "${DR_CTL}get_script/disk_info" -o "${MUNKIPATH}preflight.d/disk_info"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/disk_info"
	exit 1
fi

# Make executable
chmod a+x "${MUNKIPATH}preflight.d/disk_info"

# Set preference to include this file in the preflight check
defaults write "${PREFPATH}" ReportItems -dict-add disk_report_model "${MUNKIPATH}preflight.d/cache/disk.plist"

