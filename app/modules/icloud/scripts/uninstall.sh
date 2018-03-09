#!/bin/bash

# Remove icloud script
rm -f "${MUNKIPATH}preflight.d/icloud"

# Remove icloud.plist cache file
rm -f "${MUNKIPATH}preflight.d/cache/icloud.plist"
