<?php header("Content-Type: text/plain");
?>#!/bin/bash

BASEURL="<?php echo
	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://')
	. $_SERVER['HTTP_HOST']
	. conf('subdirectory'); ?>"
TPL_BASE="${BASEURL}/assets/client_installer/"
MUNKIPATH="/usr/local/munki/" # TODO read munkipath from munki config
CACHEPATH="${MUNKIPATH}preflight.d/cache/"
PREFPATH="/Library/Preferences/MunkiReport"
PREFLIGHT=1
CURL="/usr/bin/curl --insecure --fail --silent  --show-error"
# Exit status
ERR=0

# Packaging
PKGTMP=""
BUILDPKG=0
IDENTIFIER="com.github.munkireport"

VERSION="<?php echo get_version(); ?>"

function usage {
	PROG=$(basename $0)
	cat <<EOF >&2
Usage: ${PROG} [OPTIONS]

  -b URL    Base url to munki report server
            Current value: ${BASEURL}
  -m PATH   Path to installation directory
            Current value: ${MUNKIPATH}
  -p PATH   Path to preferences file (without the .plist extension)
            Current value: ${PREFPATH}
  -n        Do not run preflight script after the installation
  -i PATH   Create a full installer at PATH
  -h        Display this help message

Example:
  * Install munkireport client scripts into the default location and run the
    preflight script.

        $PROG

  * Install munkireport and preferences into a custom location ready to be
    packaged.

        $PROG -b ${BASEURL} \\
              -m ~/Desktop/munkireport-$VERSION/usr/local/munki/ \\
              -p ~/Desktop/munkireport-$VERSION/Library/Preferences/MunkiReport \\
              -n

  * Create a package installer for munkireport.

        $PROG -i ~/Desktop
EOF
}

while getopts b:m:p:i:nh flag; do
	case $flag in
		b)
			BASEURL="$OPTARG"
			;;
		m)
			MUNKIPATH="$OPTARG"
			;;
		p)
			PREFPATH="$OPTARG"
			;;
		i)
			PKGDEST="$OPTARG"
			# Create temp directory
			PKGTMP=$(mktemp -d -t mrpkg)
			MUNKIPATH="$PKGTMP"/usr/local/munki/
			PREFPATH="$PKGTMP"/Library/Preferences/MunkiReport
			PREFLIGHT=0
			BUILDPKG=1
			;;
		n)
			PREFLIGHT=0
			;;
		h|?)
			usage
			exit
			;;
	esac
done

echo "Preparing ${MUNKIPATH} and ${PREFPATH}"
mkdir -p "$(dirname ${PREFPATH})"
mkdir -p "${MUNKIPATH}munkilib"

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
<?php foreach($install_scripts AS $scriptname => $filepath): ?>

<?php echo "## $scriptname ##"; ?> 
echo '+ Installing <?php echo $scriptname; ?>'

<?php echo file_get_contents($filepath); ?>

<?php endforeach; ?>

<?php foreach($uninstall_scripts AS $scriptname => $filepath): ?>

<?php echo "## $scriptname ##"; ?> 
echo '- Uninstalling <?php echo $scriptname; ?>'

<?php echo file_get_contents($filepath); ?>

<?php endforeach; ?>

# Remove munkireport version file
rm -f "${MUNKIPATH}munkireport-"*

if [ $ERR = 0 ]; then

	if [ $BUILDPKG = 1 ]; then

		echo "Building MunkiReport v${VERSION} package."
		pkgbuild --identifier "$IDENTIFIER" \
				 --version "$VERSION" \
				 --root "$PKGTMP" \
				 "$PKGDEST/munkireport-${VERSION}.pkg"

	else

		# Set munkireport version file
		touch "${MUNKIPATH}munkireport-${VERSION}"

		echo "Installation of MunkiReport v${VERSION} complete."
		echo 'Running the preflight script for initialization'
		if [ $PREFLIGHT = 1 ]; then
			${MUNKIPATH}preflight
		fi

	fi
	
else
	echo "! Installation of MunkiReport v${VERSION} incomplete."
fi

if [ "$PKGTMP" != "" ]; then
	echo "Cleaning up temporary files"
	rm -r $PKGTMP
fi

exit $ERR
