#!/bin/bash

# Remove location script
rm -f "${MUNKIPATH}preflight.d/location.py"

# Remove location.plist file
rm -f "${MUNKIPATH}preflight.d/cache/location.plist"
