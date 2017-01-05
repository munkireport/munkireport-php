#!/bin/bash

rm -f "${MUNKIPATH}preflight.d/appusage"
rm -f "${CACHEPATH}appusage.txt"

# Stop crankd daemon and remove crankd and database
# Commented out to keep crankd around as it may be used by other services or scripts

#/bin/launchctl unload -w /Library/LaunchDaemons/com.googlecode.pymacadmin.crankd.plist
#rm -rf '/Library/Application Support/crankd/'
#rm -f /Library/LaunchDaemons/com.googlecode.pymacadmin.crankd.plist
#rm -f /Library/Preferences/com.googlecode.pymacadmin.crankd.plist
#rm -f /usr/local/sbin/crankd.py
#rm -f /var/db/application_usage.sqlite