#!/bin/bash

# Remove ios_devices script
rm -f "${MUNKIPATH}preflight.d/ios_devices"

# Remove ios_devices.plist cache file
rm -f "${MUNKIPATH}preflight.d/cache/ios_devices.plist"
