#!/bin/bash

# Remove current version of files
rm -f "${MUNKIPATH}preflight.d/security.py"
rm -f "${CACHEPATH}security.plist"

# Remove older version of files
rm -f "${MUNKIPATH}preflight.d/security.sh"
rm -f "${CACHEPATH}security.txt"
