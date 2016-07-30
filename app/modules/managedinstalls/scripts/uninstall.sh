#!/bin/bash

# Remove managedinstalls script
rm -f "${MUNKIPATH}preflight.d/managedinstalls.py"

# Remove managedinstalls.plist file
rm -f "${MUNKIPATH}preflight.d/cache/managedinstalls.plist"
