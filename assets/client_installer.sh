#!/bin/bash
#
# Use this script to install the munkireport client on each client machine.
#
# Step 1: Edit the BASEURL variable (be sure to include a trailing '/'

BASEURL="http://192.168.1.64/munkireport-php/"

# Step 2: Edit assets/client_installer/MunkiReport.plist
#	        All you need to do here is edit the preinstall_script to use a valid URL
# Step 3: Copy MunkiReport.plist file into your munki repo
# Step 4: Add "munkireport" to the 'managed_installs' array of at least one
#         manifest
# Step 5: Run /usr/local/makecatalogs
# Step 6: Drink coffee. Watch data. Rule all that you survey.
#




TPL_BASE="${BASEURL}/assets/client_installer/"
VERSION="1.0.0"
MUNKIPATH="/usr/local/munki"
PREFPATH="/Library/Preferences/MunkiReport"

#### Install Munkireport ####

curl --silent "${TPL_BASE}preflight" -o "$MUNKIPATH/preflight" \
	&& curl --silent "${TPL_BASE}postflight" -o "$MUNKIPATH/postflight" \
	&& curl --silent "${TPL_BASE}report_broken_client" -o "$MUNKIPATH/report_broken_client" \
	&& curl --silent "${TPL_BASE}reportcommon" -o "$MUNKIPATH/munkilib/reportcommon.py" \

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm "${MUNKIPATH}/preflight" \
		"${MUNKIPATH}/postflight" \
		"${MUNKIPATH}/report_broken_client" \
		"${MUNKIPATH}/munkilib/reportcommon.py"
	exit 1
fi

chmod a+x "$MUNKIPATH/"{preflight,postflight,report_broken_client}
rm -f /usr/local/munki/munkireport-*
touch "/usr/local/munki/munkireport-${VERSION}"

#### Configure Munkireport ####

defaults write $PREFPATH BaseUrl "${BASEURL}"
defaults delete $PREFPATH ReportItems
# Add munkireport
defaults write $PREFPATH ReportItems -dict-add Munkireport "/Library/Managed Installs/ManagedInstallReport.plist"

# Add InstallHistory (in 10.5 you need SWU.log)
if [[ `uname -r` < '10.0.0' ]]
then 
	IPATH="/Library/Logs/Software Update.log"
else
	IPATH="/Library/Receipts/InstallHistory.plist"
fi
defaults write $PREFPATH ReportItems -dict-add InstallHistory "$IPATH"

# Add inventory
defaults write $PREFPATH ReportItems -dict-add InventoryItem "/Library/Managed Installs/ApplicationInventory.plist"

echo "Installation of MunkiReport v${VERSION} complete."
exit 0
