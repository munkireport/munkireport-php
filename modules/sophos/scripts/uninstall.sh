#!/bin/bash

MODULE_NAME="sophos"
MODULESCRIPT="sophos.py"
MODULE_CACHE_FILE="sophos.plist"

# Remove preflight script
rm -f "${MUNKIPATH}preflight.d/sophos.py"

# Remove cache file
rm -f "${CACHEPATH}sophos.plist"
