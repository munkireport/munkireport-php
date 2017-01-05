#!/bin/bash

MODULE_NAME="appusage"
MODULE_CACHE_FILE="appusage.txt"

CTL="${BASEURL}index.php?/module/${MODULE_NAME}/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/appusage" -o "${MUNKIPATH}preflight.d/appusage"

if [ ! -f '/Library/Application Support/crankd/ApplicationUsage.py' ]; then
"${CURL[@]}" "${CTL}get_script/crankd_payload.zip" -o "/tmp/crankd_payload.zip"
fi

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/appusage"
	touch "${CACHEPATH}${MODULE_CACHE_FILE}"

	# Check if crankd is already installed
	if [ -f /tmp/crankd_payload.zip ]; then
	# Unzip crankd and install
	unzip -qq -o /tmp/crankd_payload.zip -d /tmp/

	# Create directories 
	mkdir -p '/Library/Application Support/crankd/PyMacAdmin/crankd/handlers/'
	mkdir -p '/Library/Application Support/crankd/PyMacAdmin/SCUtilities/'
	mkdir -p '/Library/Application Support/crankd/PyMacAdmin/Security/tests/'
	mkdir -p '/usr/local/sbin/'

	# Copy files into directories
	cp -f /tmp/payload/usr/local/sbin/crankd.py /usr/local/sbin/crankd.py
	cp -f /tmp/payload/Library/Preferences/com.googlecode.pymacadmin.crankd.plist /Library/Preferences/com.googlecode.pymacadmin.crankd.plist
	cp -f /tmp/payload/Library/LaunchDaemons/com.googlecode.pymacadmin.crankd.plist /Library/LaunchDaemons/com.googlecode.pymacadmin.crankd.plist
	cp -f '/tmp/payload/Library/Application Support/crankd/ApplicationUsage.py' '/Library/Application Support/crankd/ApplicationUsage.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/NSWorkspaceHandler.py' '/Library/Application Support/crankd/NSWorkspaceHandler.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/__init__.py' '/Library/Application Support/crankd/PyMacAdmin/__init__.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/__init__.pyc' '/Library/Application Support/crankd/PyMacAdmin/__init__.pyc'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/crankd/__init__.py' '/Library/Application Support/crankd/PyMacAdmin/crankd/__init__.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/crankd/handlers/__init__.py' '/Library/Application Support/crankd/PyMacAdmin/crankd/handlers/__init__.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/SCUtilities/__init__.py' '/Library/Application Support/crankd/PyMacAdmin/SCUtilities/__init__.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/SCUtilities/SCPreferences.py' '/Library/Application Support/crankd/PyMacAdmin/SCUtilities/SCPreferences.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/Security/__init__.py' '/Library/Application Support/crankd/PyMacAdmin/Security/__init__.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/Security/__init__.pyc' '/Library/Application Support/crankd/PyMacAdmin/Security/__init__.pyc'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/Security/Keychain.py' '/Library/Application Support/crankd/PyMacAdmin/Security/Keychain.py'
	cp -f '/tmp/payload/Library/Application Support/crankd/PyMacAdmin/Security/tests/test_Keychain.py' '/Library/Application Support/crankd/PyMacAdmin/Security/tests/test_Keychain.py'

	# Start crankd daemon - cuz restarting is no fun :P
	/bin/launchctl load -w /Library/LaunchDaemons/com.googlecode.pymacadmin.crankd.plist

	# Clean up
	rm -rf /tmp/payload/
	rm -f /tmp/crankd_payload.zip
	fi

	# Set preference to include this file in the preflight check
	setreportpref $MODULE_NAME "${CACHEPATH}${MODULE_CACHE_FILE}"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/appusage"
	rm -f "/tmp/crankd_payload.zip"
	rm -rf "/tmp/payload/"

	# Signal that we had an error
	ERR=1
fi
