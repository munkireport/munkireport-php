<?php header("Content-Type: text/plain");
?>#!/bin/sh

BASEURL="<?php echo
	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://')
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
curl --fail --silent "${TPL_BASE}submit.preflight" --remote-name

if [ "${?}" != 0 ]
then
	echo "Failed to download preflight scripts!"
	rm -f "${MUNKIPATH}preflight.d/submit.preflight"
else
	chmod a+x "${MUNKIPATH}preflight.d/submit.preflight"
fi

echo "Configuring munkireport"
#### Configure Munkireport ####

defaults write "${PREFPATH}" BaseUrl "${BASEURL}"
defaults delete "${PREFPATH}" ReportItems
# Add munkireport
defaults write "${PREFPATH}" ReportItems -dict-add Munkireport "/Library/Managed Installs/ManagedInstallReport.plist"

# Add inventory
defaults write "${PREFPATH}" ReportItems -dict-add InventoryItem "/Library/Managed Installs/ApplicationInventory.plist"

<?foreach($scripts AS $scriptname => $code):?>

<?="## $scriptname ##"?> 
<?=file_get_contents($code)?>

<?endforeach?>

echo "Installation of MunkiReport v${VERSION} complete."
exit 0
