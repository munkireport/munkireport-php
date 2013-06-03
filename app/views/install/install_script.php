#!/bin/sh


BASEURL="<?='http:'.WEB_HOST.WEB_FOLDER?>"
MUNKIPATH="/usr/local/munki"
PREFPATH="/Library/Preferences/MunkiReport"

echo "BaseURL is ${BASEURL}"

echo 'retrieving munkireport scripts'

curl -s "${BASEURL}scripts/preflight" > "$MUNKIPATH/preflight" || exit 1
curl -s "${BASEURL}scripts/postflight" > "$MUNKIPATH/postflight" || exit 1
curl -s "${BASEURL}scripts/report_broken_client" > "$MUNKIPATH/report_broken_client" || exit 1
curl -s "${BASEURL}scripts/reportcommon" > "$MUNKIPATH/munkilib/reportcommon.py" || exit 1
	
chmod a+x "$MUNKIPATH/"{preflight,postflight,report_broken_client}

echo 'configure munkireport'

#### Configure Munkireport ####

defaults write $PREFPATH BaseUrl "$BASEURL"
defaults delete $PREFPATH ReportItems
# Add munkireport
defaults write $PREFPATH ReportItems -dict-add Munkireport "/Library/Managed\ Installs/ManagedInstallReport.plist"

# Add InstallHistory (in 10.5 you need SWU.log)
if [[ `uname -r` < '10.0.0' ]]; then 
	IPATH="/Library/Logs/Software Update.log"
else
	IPATH="/Library/Receipts/InstallHistory.plist"
fi
defaults write $PREFPATH ReportItems -dict-add InstallHistory "$IPATH"

# Add inventory
defaults write $PREFPATH ReportItems -dict-add InventoryItem "/Library/Managed\ Installs/ApplicationInventory.plist"