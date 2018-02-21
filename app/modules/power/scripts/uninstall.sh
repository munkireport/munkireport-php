#!/bin/bash

# Remove power script
rm -f "${MUNKIPATH}preflight.d/power.sh"

# Remove powerinfo.txt file
rm -f "${MUNKIPATH}preflight.d/cache/powerinfo.plist"
