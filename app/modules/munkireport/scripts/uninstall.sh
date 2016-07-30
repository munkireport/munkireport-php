#!/bin/bash

# Remove munkireport script
rm -f "${MUNKIPATH}preflight.d/munkireport.py"

# Remove munkireport.plist file
rm -f "${MUNKIPATH}preflight.d/cache/munkireport.plist"
