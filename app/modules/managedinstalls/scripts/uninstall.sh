#!/bin/bash

# Remove previous script from preflight.d and postflight.d
rm -f "${MUNKIPATH}preflight.d/managedinstalls.py"
rm -f "${MUNKIPATH}postlight.d/managedinstalls.py"

# Remove managedinstalls.plist files
rm -f "${MUNKIPATH}preflight.d/cache/managedinstalls.plist"
rm -f "${MUNKIPATH}postflight.d/cache/managedinstalls.plist"
