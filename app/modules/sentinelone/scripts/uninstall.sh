#!/bin/bash

# Remove preflight script
rm -f "${MUNKIPATH}preflight.d/sentinelone.py"

# Remove cache file
rm -f "${CACHEPATH}sentinelone.plist"
