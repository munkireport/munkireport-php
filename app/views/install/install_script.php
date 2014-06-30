<?php header("Content-Type: text/plain");
?>#!/bin/sh

BASEURL="<?php echo
	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://')
	. $_SERVER['HTTP_HOST']
	. conf('subdirectory'); ?>"
TPL_BASE="${BASEURL}/assets/client_installer/"
MUNKIPATH="/usr/local/munki/" # TODO read munkipath from munki config
PREFPATH="/Library/Preferences/MunkiReport"
CURL="/usr/bin/curl --insecure --fail --silent  --show-error"
# Exit status
ERR=0
VERSION="<?=get_version()?>"

echo "BaseURL is ${BASEURL}"

echo "Retrieving munkireport scripts"

cd ${MUNKIPATH}
$CURL "${TPL_BASE}{preflight,postflight,report_broken_client}" --remote-name --remote-name --remote-name \
	&& $CURL "${TPL_BASE}reportcommon" -o "${MUNKIPATH}munkilib/reportcommon.py" \
	&& $CURL "${TPL_BASE}phpserialize" -o "${MUNKIPATH}munkilib/phpserialize.py"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}"{preflight,postflight,report_broken_client} \
		"${MUNKIPATH}"munkilib/reportcommon.py
	exit 1
fi

chmod a+x "${MUNKIPATH}"{preflight,postflight,report_broken_client}

# Create preflight.d + download scripts
mkdir -p "${MUNKIPATH}preflight.d"
cd "${MUNKIPATH}preflight.d"
${CURL} "${TPL_BASE}submit.preflight" --remote-name

if [ "${?}" != 0 ]
then
	echo "Failed to download preflight script!"
	rm -f "${MUNKIPATH}preflight.d/submit.preflight"
else
	chmod a+x "${MUNKIPATH}preflight.d/submit.preflight"
fi

# Create postflight.d
mkdir -p "${MUNKIPATH}postflight.d"

# Create preflight_abort.d
mkdir -p "${MUNKIPATH}preflight_abort.d"

echo "Configuring munkireport"
#### Configure Munkireport ####

defaults write "${PREFPATH}" BaseUrl "${BASEURL}"

# Reset ReportItems array
defaults write "${PREFPATH}" ReportItems -dict

# Include module scripts
<?foreach($install_scripts AS $scriptname => $filepath):?>

<?="## $scriptname ##"?> 
echo '+ Installing <?=$scriptname?>'

<?=file_get_contents($filepath)?>

<?endforeach?>

<?foreach($uninstall_scripts AS $scriptname => $filepath):?>

<?="## $scriptname ##"?> 
echo '- Uninstalling <?=$scriptname?>'

<?=file_get_contents($filepath)?>

<?endforeach?>

# Remove munkireport version file
rm -f "${MUNKIPATH}munkireport-"*

if [ $ERR = 0 ]; then

	# Set munkireport version file
	touch "${MUNKIPATH}munkireport-${VERSION}"

	echo "Installation of MunkiReport v${VERSION} complete."
	echo 'Running the preflight script for initialization'
	${MUNKIPATH}preflight
	
else
	echo "! Installation of MunkiReport v${VERSION} incomplete."
fi

exit $ERR
