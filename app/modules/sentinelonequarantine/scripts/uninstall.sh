#!/bin/bash

MODULESCRIPT="sentinelone_quarantine.py"
QUARANTINE_CACHE_FILE="sentinelone_quarantine.plist"

# Remove preflight script
rm -f "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

# Remove cache file
rm -f "${CACHEPATH}${QUARANTINE_CACHE_FILE}"
