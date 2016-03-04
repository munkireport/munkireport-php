#!/bin/bash

# map controller
CTL="${BASEURL}index.php?/module/location/"

# Path to location directory and pref
LOCATION_DIR="/Library/Application Support/pinpoint"
LOCATION_PREF="${LOCATION_DIR}/location.plist"

# Set preference to include this file in the preflight check
setreportpref "location" "${LOCATION_PREF}"

# Remove old testing script and cache if they exist
files=( "${MUNKIPATH}preflight.d/location.py" "${MUNKIPATH}preflight.d/cache/location.plist" )
for i in "${files[@]}"
do
	/bin/rm -f $i
done

# Set report preferences if pinpoint hasn't ran a check yet
LastRun=$(/bin/date -u "+%Y-%m-%d %H:%M:%S +0000")
CurrentStatus="pinpoint has not ran or been installed"

# If pref doesn't exist run
if [ ! -f "${LOCATION_PREF}" ]; then
  /bin/mkdir -p "${LOCATION_DIR}"
  /usr/bin/defaults write "${LOCATION_PREF}" LastRun -string "${LastRun}"
  /usr/bin/defaults write "${LOCATION_PREF}" LastLocationRun -string "${LastRun}"
  /usr/bin/defaults write "${LOCATION_PREF}" CurrentStatus -string "${CurrentStatus}"
  # Change permissions to match regular preference files
  /bin/chmod 644 "${LOCATION_PREF}"
fi