#!/bin/bash

# Remove launchdaemons script
rm -f "${MUNKIPATH}preflight.d/launchdaemons"

# Remove launchdaemons.plist cache file
rm -f "${MUNKIPATH}preflight.d/cache/launchdaemons.plist"
