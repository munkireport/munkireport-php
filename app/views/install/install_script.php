<?php header("Content-Type: text/plain");
?>#!/bin/bash

BASEURL="<?php echo conf('webhost') . conf('subdirectory'); ?>"
MUNKIPATH="/usr/local/munki/" # TODO read munkipath from munki config
CACHEPATH="${MUNKIPATH}preflight.d/cache/"
POSTFLIGHT_CACHEPATH="${MUNKIPATH}postflight.d/cache/"
PREFPATH="/Library/Preferences/MunkiReport"
PREFLIGHT=1
PREF_CMDS=( ) # Pref commands array
TARGET_VOLUME=''
CURL=("<?php echo implode('" "', conf('curl_cmd'))?>")
PREFLIGHT_SCRIPT="<?php echo conf('preflight_script'); ?>"
POSTFLIGHT_SCRIPT="<?php echo conf('postflight_script'); ?>"
REPORT_BROKEN_CLIENT_SCRIPT="<?php echo conf('report_broken_client_script'); ?>"
# Exit status
ERR=0

# Packaging
BUILDPKG=0
IDENTIFIER="com.github.munkireport"
RESULT=""

VERSION="<?php echo get_version(); ?>"
VERSIONLONG="<?php echo $GLOBALS['version']; ?>"

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
  -c ID     Change pkg id to ID
  -h        Display this help message
  -r PATH   Path to installer result plist
  -v VERS   Override version number

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

# Set munkireport preference
function setpref {
	PREF_CMDS=( "${PREF_CMDS[@]}" "defaults write \"\${TARGET}\"${PREFPATH} ${1} \"${2}\"" )
}

# Set munkireport reportitem preference
function setreportpref {
	setpref "ReportItems -dict-add ${1}" "${2}"
}

# Reset reportitems
function resetreportpref {
	PREF_CMDS=( "${PREF_CMDS[@]}" "defaults write \"\${TARGET}\"${PREFPATH} ReportItems -dict" )
}

while getopts b:m:p:r:c:v:i:nh flag; do
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
		r)
			RESULT="$OPTARG"
			;;
		c)
			IDENTIFIER="$OPTARG"
			;;
		v)
			VERSION="$OPTARG"
			;;
		i)
			PKGDEST="$OPTARG"
			# Create temp directory
			INSTALLTEMP=$(mktemp -d -t mrpkg)
			INSTALLROOT="$INSTALLTEMP"/install_root
			MUNKIPATH="$INSTALLROOT"/usr/local/munki/
			TARGET_VOLUME='$3'
			PREFPATH="/Library/Preferences/MunkiReport"
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

# build additional HTTP headers
if [ "$(defaults read "${PREFPATH}" UseMunkiAdditionalHttpHeaders 2>/dev/null)" = "1" ]; then
	BUNDLE_ID='ManagedInstalls'
	MANAGED_INSTALLS_PLIST_PATHS=("${TARGET_VOLUME}/private/var/root/Library/Preferences/${BUNDLE_ID}.plist" "${TARGET_VOLUME}/Library/Preferences/${BUNDLE_ID}.plist")
	ADDITIONAL_HTTP_HEADERS_KEY='AdditionalHttpHeaders'
	ADDITIONAL_HTTP_HEADERS=()
	for plist in "${MANAGED_INSTALLS_PLIST_PATHS[@]}"; do
		while IFS= read -r line; do
			if [[ "$line" =~ \"([^\"]+) ]]; then
				ADDITIONAL_HTTP_HEADERS+=("${BASH_REMATCH[1]}")
			fi
		done <<< "$(defaults read "${plist%.plist}" "$ADDITIONAL_HTTP_HEADERS_KEY")"
	done
	for header in "${ADDITIONAL_HTTP_HEADERS[@]}"; do CURL+=("-H" "$header"); done
fi

echo "Preparing ${MUNKIPATH}"
mkdir -p "${MUNKIPATH}munkilib"

echo "BaseURL is ${BASEURL}"
TPL_BASE="${BASEURL}/assets/client_installer/"

echo "Retrieving munkireport scripts"

cd ${MUNKIPATH}
"${CURL[@]}" "${TPL_BASE}preflight" --output "${PREFLIGHT_SCRIPT}" \
  "${TPL_BASE}postflight" --output "${POSTFLIGHT_SCRIPT}" \
  "${TPL_BASE}report_broken_client" --output "${REPORT_BROKEN_CLIENT_SCRIPT}" \
    && "${CURL[@]}" "${TPL_BASE}purl" -o "${MUNKIPATH}munkilib/purl.py" \
    && "${CURL[@]}" "${TPL_BASE}reportcommon" -o "${MUNKIPATH}munkilib/reportcommon.py" \
	&& "${CURL[@]}" "${TPL_BASE}phpserialize" -o "${MUNKIPATH}munkilib/phpserialize.py"

if [ "${?}" != 0 ]
then
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}"{${PREFLIGHT_SCRIPT},${POSTFLIGHT_SCRIPT},${REPORT_BROKEN_CLIENT_SCRIPT}} \
		"${MUNKIPATH}"munkilib/reportcommon.py
	exit 1
fi

chmod a+x "${MUNKIPATH}"{${PREFLIGHT_SCRIPT},${POSTFLIGHT_SCRIPT},${REPORT_BROKEN_CLIENT_SCRIPT}}

# Create preflight.d + download scripts
mkdir -p "${MUNKIPATH}preflight.d"
cd "${MUNKIPATH}preflight.d"
${CURL[@]} "${TPL_BASE}submit.preflight" --remote-name

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

# Set BaseUrl preference
setpref 'BaseUrl' "${BASEURL}"

# Reset ReportItems array
resetreportpref

# Include module scripts
<?php foreach($install_scripts AS $scriptname => $filepath): ?>

<?php echo "## $scriptname ##\n"; ?>
echo '+ Installing <?php echo $scriptname; ?>'

<?php echo file_get_contents($filepath); ?>

<?php endforeach; ?>

# Store munkipath when building a package
if [ $BUILDPKG = 1 ]; then
	STOREPATH=${MUNKIPATH}
	MUNKIPATH='$3/usr/local/munki/'
	CACHEPATH="\$3${CACHEPATH}"
	POSTFLIGHT_CACHEPATH="\$3${POSTFLIGHT_CACHEPATH}"
fi

# Capture uninstall scripts
read -r -d '' UNINSTALLS << EOF

<?php foreach($uninstall_scripts AS $scriptname => $filepath): ?>

<?php echo "## $scriptname ##\n"; ?>
echo '- Uninstalling <?php echo $scriptname; ?>'

<?php echo file_get_contents($filepath); ?>

<?php endforeach; ?>

EOF

# Restore munkipath when building a package
if [ $BUILDPKG = 1 ]; then
	MUNKIPATH=${STOREPATH}
fi


# If not building a package, execute uninstall scripts
if [ $BUILDPKG = 0 ]; then
	eval "$UNINSTALLS"
	# Remove munkireport version file
	rm -f "${MUNKIPATH}munkireport-"*
fi

if [ $ERR = 0 ]; then

	if [ $BUILDPKG = 1 ]; then

		# Create scripts directory
		SCRIPTDIR="$INSTALLTEMP"/scripts
		mkdir -p "$SCRIPTDIR"

		# Add uninstall script to preinstall
		echo  "#!/bin/bash" > $SCRIPTDIR/preinstall
		# Add uninstall scripts
		echo  "$UNINSTALLS" >> $SCRIPTDIR/preinstall
		chmod +x $SCRIPTDIR/preinstall

		# Add Preference setting commands to postinstall
		echo  "#!/bin/bash" > $SCRIPTDIR/postinstall
        cat >>$SCRIPTDIR/postinstall <<EOF
if [[ "\$3" == "/" ]]; then
    TARGET=""
else
    TARGET="\$3"
fi

EOF

		for i in "${PREF_CMDS[@]}";
			do echo $i >> $SCRIPTDIR/postinstall
		done
        echo "defaults write \"\${TARGET}\"${PREFPATH} Version ${VERSIONLONG}" >> $SCRIPTDIR/postinstall
		chmod +x $SCRIPTDIR/postinstall


		echo "Building MunkiReport v${VERSION} package."
		pkgbuild --identifier "$IDENTIFIER" \
				 --version "$VERSION" \
				 --root "$INSTALLROOT" \
				 --scripts "$SCRIPTDIR" \
				 "$PKGDEST/munkireport-${VERSION}.pkg"

		if [[ $RESULT ]]; then
			defaults write "$RESULT" version ${VERSION}
			defaults write "$RESULT" pkg_path "$PKGDEST/munkireport-${VERSION}.pkg"
		fi

	else

		echo "Preparing ${PREFPATH}"
		mkdir -p "$(dirname ${PREFPATH})"

		# Set preferences
		echo "Setting preferences"
		for i in "${PREF_CMDS[@]}"; do
			eval $i
		done

		# Set munkireport version file
		touch "${MUNKIPATH}munkireport-${VERSION}"
        defaults write ${PREFPATH} Version ${VERSIONLONG}

		echo "Installation of MunkiReport v${VERSION} complete."
		echo 'Running the preflight script for initialization'
		if [ $PREFLIGHT = 1 ]; then
			${MUNKIPATH}preflight
		fi

	fi

else
	echo "! Installation of MunkiReport v${VERSION} incomplete."
fi

if [ "$INSTALLTEMP" != "" ]; then
	echo "Cleaning up temporary directory $INSTALLTEMP"
	rm -r $INSTALLTEMP
fi



exit $ERR
