<?php header("Content-Type: text/plain");
?>#!/bin/sh

BASEURL="<?php echo
	(isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
	. $_SERVER['HTTP_HOST']
	. conf('subdirectory'); ?>"
TPL_BASE="${BASEURL}/assets/client_installer/"
MUNKIPATH="/usr/local/munki/" # TODO read munkipath from munki config
PREFPATH="/Library/Preferences/MunkiReport"
VERSION="1.0.0"

echo "BaseURL is ${BASEURL}"

echo "Retrieving munkireport scripts"

cd ${MUNKIPATH}
curl --fail --silent "${TPL_BASE}{preflight,postflight,report_broken_client}" --remote-name --remote-name --remote-name \
	&& curl --fail --silent "${TPL_BASE}reportcommon" -o "${MUNKIPATH}munkilib/reportcommon.py" \
	&& curl --fail --silent "${TPL_BASE}phpserialize" -o "${MUNKIPATH}munkilib/phpserialize.py"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}"{preflight,postflight,report_broken_client} \
		"${MUNKIPATH}"munkilib/reportcommon.py
	exit 1
fi

chmod a+x "${MUNKIPATH}"{preflight,postflight,report_broken_client}
rm "${MUNKIPATH}munkireport-"* 2>/dev/null
touch "${MUNKIPATH}munkireport-${VERSION}"

# Create preflight.d + download scripts
mkdir -p "${MUNKIPATH}preflight.d"
cd "${MUNKIPATH}preflight.d"
curl --fail --silent "${TPL_BASE}{submit.preflight,disk_info}" --remote-name --remote-name

if [ "${?}" != 0 ]
then
	echo "Failed to download preflight scripts!"
	rm "${MUNKIPATH}preflight.d/"{submit.preflight,disk_info}
else
	chmod a+x "${MUNKIPATH}preflight.d/"{submit.preflight,disk_info}
fi

echo "Configuring munkireport"
#### Configure Munkireport ####

defaults write "${PREFPATH}" BaseUrl "${BASEURL}"
defaults delete "${PREFPATH}" ReportItems
# Add munkireport
defaults write "${PREFPATH}" ReportItems -dict-add Munkireport "/Library/Managed Installs/ManagedInstallReport.plist"

# Add InstallHistory (in 10.5 you need SWU.log)
if [[ `uname -r` < '10.0.0' ]]; then 
	IPATH="/Library/Logs/Software Update.log"
else
	IPATH="/Library/Receipts/InstallHistory.plist"
fi
defaults write "${PREFPATH}" ReportItems -dict-add InstallHistory "${IPATH}"

# Add inventory
defaults write "${PREFPATH}" ReportItems -dict-add InventoryItem "/Library/Managed Installs/ApplicationInventory.plist"

# Add disk_info
defaults write "${PREFPATH}" ReportItems -dict-add disk_report_model "${MUNKIPATH}preflight.d/cache/disk.plist"

echo "Installation of MunkiReport v${VERSION} complete."
exit 0
