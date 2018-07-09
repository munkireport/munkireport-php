#!/bin/bash

# Remove preflight script
rm -f "${MUNKIPATH}preflight.d/sentinelone_quarantine.py"

# Remove cache file
rm -f "${CACHEPATH}sentinelone_quarantine.plist"
