<?php header("Content-Type: text/plain");
?>#!/bin/sh

BASEURL="<?php echo
	(isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
	. $_SERVER['HTTP_HOST']
	. WEB_FOLDER; ?>"
TPL_BASE="${BASEURL}/assets/client_installer/"
MUNKIPATH="/usr/local/munki"
PREFPATH="/Library/Preferences/MunkiReport"
VERSION="1.0.0"

echo "BaseURL is ${BASEURL}"

echo "Retrieving munkireport scripts"

curl --silent "${TPL_BASE}preflight" -o "${MUNKIPATH}/preflight" \
	&& curl --silent "${TPL_BASE}postflight" -o "${MUNKIPATH}/postflight" \
	&& curl --silent "${TPL_BASE}report_broken_client" -o "${MUNKIPATH}/report_broken_client" \
	&& curl --silent "${TPL_BASE}reportcommon" -o "${MUNKIPATH}/munkilib/reportcommon.py"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm "${MUNKIPATH}/preflight" \
		"${MUNKIPATH}/postflight" \
		"${MUNKIPATH}/report_broken_client" \
		"${MUNKIPATH}/munkilib/reportcommon.py"
	exit 1
fi

chmod a+x "${MUNKIPATH}/"{preflight,postflight,report_broken_client}
rm "${MUNKIPATH}/munkireport-"* 2>/dev/null
touch "${MUNKIPATH}/munkireport-${VERSION}"

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

echo "Installation of MunkiReport v${VERSION} complete."
exit 0
