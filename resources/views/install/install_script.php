<?php header("Content-Type: text/plain");
?>#!/bin/bash

BASEURL="<?php echo url('/'); ?>/"
INSTALLROOT=""
MUNKIPATH="/usr/local/munkireport/"
CACHEPATH="${MUNKIPATH}scripts/cache/"
POSTFLIGHT_CACHEPATH="${MUNKIPATH}scripts/cache/"
PREFPATH="/Library/Preferences/MunkiReport"
PREF_CMDS=( ) # Pref commands array
TARGET_VOLUME=''
CURL=("<?php echo implode('" "', config('_munkireport.curl_cmd'))?>")
POSTFLIGHT_SCRIPT="<?php echo config('_munkireport.postflight_script'); ?>"
REPORT_BROKEN_CLIENT_SCRIPT="<?php echo config('_munkireport.report_broken_client_script'); ?>"
# Exit status
ERR=0

# Packaging
BUILDPKG=0
IDENTIFIER="com.github.munkireport"
RESULT=""

VERSION="<?php echo get_version(); ?>"
VERSIONLONG="<?php echo $GLOBALS['version']; ?>"

function usage {
	PROG=$(/usr/bin/basename $0)
	/bin/cat <<EOF >&2
Usage: ${PROG} [OPTIONS]

  -b URL   Base url to munki report server
            Current value: ${BASEURL}
  -m PATH  Path to installation directory
            Current value: ${MUNKIPATH}
  -p PATH  Path to preferences file (without the .plist extension)
            Current value: ${PREFPATH}
  -i PATH  Create a full installer at PATH
  -c ID    Change pkg id to ID
  -h       Display this help message
  -r PATH  Path to installer result plist
  -v VERS  Override version number
  -u       Do not set the base url in the post install script

Example:
  * Install munkireport client scripts into the default location.

        $PROG

  * Install munkireport and preferences into a custom location ready to be
    packaged.

        $PROG -b ${BASEURL} \\
              -m ~/Desktop/munkireport-$VERSION/usr/local/munkireport/ \\
              -p ~/Desktop/munkireport-$VERSION/Library/Preferences/MunkiReport \\
              -u

  * Create a package installer for munkireport.

        $PROG -i ~/Desktop
EOF
}

# Set munkireport preference
function setpref {
	PREF_CMDS=( "${PREF_CMDS[@]}" "/usr/bin/defaults write \"\${TARGET}\"${PREFPATH} ${1} \"${2}\"" )
}

# Set munkireport reportitem preference
function setreportpref {
	setpref "ReportItems -dict-add ${1}" "${2}"
}

# Reset reportitems
function resetreportpref {
	PREF_CMDS=( "${PREF_CMDS[@]}" "/usr/bin/defaults write \"\${TARGET}\"${PREFPATH} ReportItems -dict" )
}

while getopts b:m:p:r:c:v:u:i:h flag; do
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
		u)
			SETBASEURL="$OPTARG"
			SETBASEURL=0
			;;
		i)
			PKGDEST="$OPTARG"
			# Create temp directory
			INSTALLTEMP=$(/usr/bin/mktemp -d -t mrpkg)
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
if [ "$(/usr/bin/defaults read "${PREFPATH}" UseMunkiAdditionalHttpHeaders 2>/dev/null)" = "1" ]; then
	BUNDLE_ID='ManagedInstalls'
	MANAGED_INSTALLS_PLIST_PATHS=("${TARGET_VOLUME}/private/var/root/Library/Preferences/${BUNDLE_ID}.plist" "${TARGET_VOLUME}/Library/Preferences/${BUNDLE_ID}.plist")
	ADDITIONAL_HTTP_HEADERS_KEY='AdditionalHttpHeaders'
	ADDITIONAL_HTTP_HEADERS=()
	for plist in "${MANAGED_INSTALLS_PLIST_PATHS[@]}"; do
		while IFS= read -r line; do
			if [[ "$line" =~ \"([^\"]+) ]]; then
				ADDITIONAL_HTTP_HEADERS+=("${BASH_REMATCH[1]}")
			fi
		done <<< "$(/usr/bin/defaults read "${plist%.plist}" "$ADDITIONAL_HTTP_HEADERS_KEY")"
	done
	for header in "${ADDITIONAL_HTTP_HEADERS[@]}"; do CURL+=("-H" "$header"); done
fi

echo "# Preparing ${MUNKIPATH}"
/bin/mkdir -p "${MUNKIPATH}munkilib"
/bin/mkdir -p "${MUNKIPATH}scripts/cache"
/bin/mkdir -p "${INSTALLROOT}/Library/MunkiReport/Logs"
/bin/chmod 600 "${MUNKIPATH}scripts/cache/" # drw------- root wheel

# Create preflight.d symlinks
/bin/rm -rf "${MUNKIPATH}preflight.d" && /bin/ln -s "scripts" "${MUNKIPATH}preflight.d"
/bin/rm -rf "${MUNKIPATH}postflight.d" && /bin/ln -s "scripts" "${MUNKIPATH}postflight.d"

# Make symlink to macadmins python3 https://github.com/macadmins/python
/bin/rm -rf "${MUNKIPATH}munkireport-python3"; /bin/ln -s "/Library/ManagedFrameworks/Python/Python3.framework/Versions/Current/bin/python3" "${MUNKIPATH}munkireport-python3"

# Add the MunkiReport folder to the machine's path
/bin/mkdir -p "${INSTALLROOT}/private/etc/paths.d/"
echo "/usr/local/munkireport" > "${INSTALLROOT}/private/etc/paths.d/munkireport"

/bin/mkdir -p "${INSTALLROOT}/usr/local/munki"
/bin/mkdir -p "${INSTALLROOT}/Library/LaunchDaemons"

# Normalize BASEURL so it has a trailing slash.
if [[ ${BASEURL: -1} != "/" ]]; then
		BASEURL="${BASEURL}/"
fi

echo "BaseURL is ${BASEURL}"
TPL_BASE="${BASEURL}assets/client_installer/payload"

echo "# Retrieving munkireport scripts"
SCRIPTS=$("${CURL[@]}" "<?php echo url('/install/get_paths'); ?>")

for SCRIPT in $SCRIPTS; do
	COMPONENTS=(${SCRIPT//;/ })
	SOURCE_FILE=${COMPONENTS[0]}
	TARGET_FILE=${COMPONENTS[1]}
	echo "${INSTALLROOT}${TARGET_FILE}"
	"${CURL[@]}" "${TPL_BASE}${SOURCE_FILE}" --output "${INSTALLROOT}${TARGET_FILE}" || ERR=1
done

if [ $ERR = 1 ]; then
	echo "Failed to download all required components! Cleaning up.."
	for SCRIPT in $SCRIPTS; do
		COMPONENTS=(${SCRIPT//;/ })
		TARGET_FILE=${COMPONENTS[1]}
		/bin/rm -f "${INSTALLROOT}${TARGET_FILE}"
	done
	exit 1
fi

/bin/chmod a+x "${INSTALLROOT}/usr/local/munki/"{${POSTFLIGHT_SCRIPT},${REPORT_BROKEN_CLIENT_SCRIPT}}
/bin/chmod a+x "${INSTALLROOT}/usr/local/munkireport/munkireport-runner"

echo "Configuring munkireport"
#### Configure Munkireport ####

# Set BaseUrl preference
if [ $SETBASEURL = 1 ]; then
	setpref 'BaseUrl' "${BASEURL}"
fi

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
	/bin/rm -f "${MUNKIPATH}"munkireport-*.*.*
fi

if [ $ERR = 0 ]; then

	if [ $BUILDPKG = 1 ]; then

		# Create scripts directory
		SCRIPTDIR="$INSTALLTEMP"/scripts
		/bin/mkdir -p "$SCRIPTDIR"

		# Add uninstall script to preinstall
		echo "#!/bin/bash" > $SCRIPTDIR/preinstall

		# Add uninstall scripts
		echo "$UNINSTALLS" >> $SCRIPTDIR/preinstall
		/bin/chmod +x $SCRIPTDIR/preinstall

		# Add Preference setting commands to postinstall
		echo "#!/bin/bash" > $SCRIPTDIR/postinstall
		/bin/cat >>$SCRIPTDIR/postinstall <<EOF
if [[ "\$3" == "/" ]]; then
	# If installing on the Mac, not building

	TARGET=""
	/bin/launchctl unload /Library/LaunchDaemons/com.github.munkireport.runner.plist
	/bin/launchctl load /Library/LaunchDaemons/com.github.munkireport.runner.plist

	# Check for and alert about MunkiReport's Python 2
	if [[ -f "/usr/local/munkireport/munkireport-python2" ]] || [[ -f "/Library/MunkiReport/Python.framework/Versions/2.7/bin/python" ]]; then
		echo " "
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		echo " "
		echo "MunkiReport's Python 2 is installed on this Mac!"
		echo "It is no longer used by MunkiReport and should be removed"
		echo "Remove it with 'sudo rm -r /usr/local/munkireport/munkireport-python2 /Library/MunkiReport/Python.framework/Versions/2.7/'"
		echo " "
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		echo " "
	fi

	# Check if we have Mac Admin Python 3.10 installed
	if [[ ! -f "/Library/ManagedFrameworks/Python/Python3.framework/Versions/3.10/bin/python3.10" ]] ; then
		echo " "
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		echo " "
		echo "No Python 3.10 detected! MunkiReport requires the Mac Admins Python 3.10 pkg"
		echo "Please download and install it from:"
		echo "https://github.com/macadmins/python/releases/tag/v3.10.9.80716"
		echo " "
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		echo " "
	fi

	# Set permissions on cache directory
	/bin/chmod 600 /usr/local/munkireport/scripts/cache/ # drw------- root wheel

else
	TARGET="\$3"
fi

EOF
		for i in "${PREF_CMDS[@]}";
			do echo $i >> $SCRIPTDIR/postinstall
		done

		echo "/usr/bin/defaults write \"\${TARGET}\"${PREFPATH} Version ${VERSIONLONG}" >> $SCRIPTDIR/postinstall
		/bin/chmod +x $SCRIPTDIR/postinstall

		echo "Building MunkiReport v${VERSION} package."
		/usr/bin/pkgbuild --identifier "$IDENTIFIER" \
				 --version "$VERSION" \
				 --root "$INSTALLROOT" \
				 --scripts "$SCRIPTDIR" \
				 "$PKGDEST/munkireport-${VERSION}.pkg"

		if [[ $RESULT ]]; then
			/usr/bin/defaults write "$RESULT" version ${VERSION}
			/usr/bin/defaults write "$RESULT" pkg_path "$PKGDEST/munkireport-${VERSION}.pkg"
		fi

	else

		echo "Preparing ${PREFPATH}"
		/bin/mkdir -p "$(/usr/bin/dirname ${PREFPATH})"

		# Set preferences
		echo "Setting preferences"
		for i in "${PREF_CMDS[@]}"; do
			eval $i
		done

		# Set munkireport version file
		/usr/bin/touch "${MUNKIPATH}munkireport-${VERSION}"
		/usr/bin/defaults write ${PREFPATH} Version ${VERSIONLONG}

		echo "Loading MunkiReport LaunchDaemon"
		/bin/launchctl unload /Library/LaunchDaemons/com.github.munkireport.runner.plist &>/dev/null
		/bin/launchctl load /Library/LaunchDaemons/com.github.munkireport.runner.plist

		echo "Installation of MunkiReport v${VERSION} complete."

	fi

else
	echo "! Installation of MunkiReport v${VERSION} incomplete."
fi

if [ "$INSTALLTEMP" != "" ]; then
	echo "Cleaning up temporary directory $INSTALLTEMP"
	/bin/rm -r $INSTALLTEMP
fi

exit $ERR
