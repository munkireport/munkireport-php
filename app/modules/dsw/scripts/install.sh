#!/bin/bash

DSPREF="/Library/Preferences/DeployStudioInfo"
CTL="${BASEURL}index.php?/module/dsw/"

${CURL} "${CTL}get_script/dswplist.sh" -o "${MUNKIPATH}preflight.d/dswplist.sh"

if [ $? = 0 ]; then
    chmod a+x "${MUNKIPATH}preflight.d/dswplist.sh"

    # Add preferences to munkireport
    defaults write "${PREFPATH}" ReportItems -dict-add dsw_model "${DSPREF}.plist"
else
    echo "Failed to download all required components!"
    rm -f "${MUNKIPATH}preflight.d/dswplist.sh"

    ERR=1
fi
