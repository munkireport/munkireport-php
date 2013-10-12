#!/bin/bash

echo 'Install filevault status report'

# filevault_status_controller
FV_CTL="${BASEURL}/index.php?/module/filevault_status/"

# Get the scripts in the proper directories
curl --fail --silent "${FV_CTL}get_script/filevault_2_status_check.sh" -o "/usr/local/bin/filevault_2_status_check.sh" \
	&& curl --fail --silent "${FV_CTL}get_script/filevaultstatus" -o "${MUNKIPATH}preflight.d/filevaultstatus" \

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f /usr/local/bin/filevault_2_status_check.sh \
		"${MUNKIPATH}preflight.d/filevaultstatus"
	exit 1
fi

# Make executable
chmod a+x /usr/local/bin/filevault_2_status_check.sh "${MUNKIPATH}preflight.d/filevaultstatus"

# Set preference to include this file in the preflight check
defaults write "${PREFPATH}" ReportItems -dict-add filevault_status_model "${MUNKIPATH}preflight.d/cache/filevaultstatus.txt"
