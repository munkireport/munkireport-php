<?php header("Content-Type: text/plain");
?>#!/bin/bash

BASEURL="<?php echo conf('webhost') . conf('subdirectory'); ?>"
INSTALLROOT=""
MUNKIPATH="/usr/local/munkireport/"
CACHEPATH="${MUNKIPATH}scripts/cache/"
POSTFLIGHT_CACHEPATH="${MUNKIPATH}scripts/cache/"
PREFPATH="/Library/Preferences/MunkiReport"
PREF_CMDS=( ) # Pref commands array
TARGET_VOLUME=''
CURL=("<?php echo implode('" "', conf('curl_cmd'))?>")
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
  -i PATH   Create a full installer at PATH
  -c ID     Change pkg id to ID
  -h        Display this help message
  -r PATH   Path to installer result plist
  -v VERS   Override version number

Example:
  * Install munkireport client scripts into the default location.

        $PROG

  * Install munkireport and preferences into a custom location ready to be
    packaged.

        $PROG -b ${BASEURL} \\
              -m ~/Desktop/munkireport-$VERSION/usr/local/munkireport/ \\
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

while getopts b:m:p:r:c:v:i:h flag; do
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
			MUNKIPATH="$INSTALLROOT"/usr/local/munkireport/
			TARGET_VOLUME='$3'
			PREFPATH="/Library/Preferences/MunkiReport"
			BUILDPKG=1

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

echo "# Preparing ${MUNKIPATH}"
mkdir -p "${MUNKIPATH}munkilib"
mkdir -p "${MUNKIPATH}scripts"
mkdir -p "${INSTALLROOT}/Library/MunkiReport/Logs"

# Create preflight.d symlinks
rm -rf "${MUNKIPATH}preflight.d" &&  ln -s "scripts" "${MUNKIPATH}preflight.d"
rm -rf "${MUNKIPATH}postflight.d" && ln -s "scripts" "${MUNKIPATH}postflight.d"

mkdir -p "${INSTALLROOT}/usr/local/munki"
mkdir -p "${INSTALLROOT}/Library/LaunchDaemons"

#Normalize BASEURL so it has a trailing slash.
if [[ ${BASEURL: -1} != "/" ]]
then
    BASEURL="${BASEURL}/"
fi

echo "BaseURL is ${BASEURL}"
TPL_BASE="${BASEURL}assets/client_installer/payload"

echo "# Retrieving munkireport scripts"
SCRIPTS=$("${CURL[@]}" "${BASEURL}index.php?/install/get_paths")

for SCRIPT in $SCRIPTS; do
	echo "${INSTALLROOT}${SCRIPT}"
	"${CURL[@]}" "${TPL_BASE}${SCRIPT}" --output "${INSTALLROOT}${SCRIPT}" || ERR=1
done

if [ $ERR = 1 ]; then
	echo "Failed to download all required components! Cleaning up.."
	for SCRIPT in $SCRIPTS; do 
		rm -f "${INSTALLROOT}${SCRIPT}"
	done
	exit 1
fi

chmod a+x "${INSTALLROOT}/usr/local/munki/"{${POSTFLIGHT_SCRIPT},${REPORT_BROKEN_CLIENT_SCRIPT}}
chmod a+x "${INSTALLROOT}/usr/local/munkireport/munkireport-runner"


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
	MUNKIPATH='$3/usr/local/munkireport/'
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
	rm -f "${MUNKIPATH}"munkireport-*.*.*
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
	/bin/launchctl unload /Library/LaunchDaemons/com.github.munkireport.runner.plist
    /bin/launchctl load /Library/LaunchDaemons/com.github.munkireport.runner.plist
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

		echo 'Loading MunkiReport LaunchDaemon'
		/bin/launchctl unload /Library/LaunchDaemons/com.github.munkireport.runner.plist &>/dev/null
		/bin/launchctl load /Library/LaunchDaemons/com.github.munkireport.runner.plist


		echo "Installation of MunkiReport v${VERSION} complete."

	fi

else
	echo "! Installation of MunkiReport v${VERSION} incomplete."
fi

if [ "$INSTALLTEMP" != "" ]; then
	echo "Cleaning up temporary directory $INSTALLTEMP"
	rm -r $INSTALLTEMP
fi



exit $ERR
