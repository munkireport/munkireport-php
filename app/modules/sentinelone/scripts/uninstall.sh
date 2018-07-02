#!/bin/bash

MODULE_NAME="sentinelone"
MODULESCRIPT="sentinelone.py"
MODULE_CACHE_FILE="sentinelone.plist"

# Remove preflight script
rm -f "${MUNKIPATH}preflight.d/sentinelone.py"

# Remove cache file
rm -f "${CACHEPATH}sentinelone.plist"
