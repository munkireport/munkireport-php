#!/bin/bash

# Remove munkireport script
rm -f "${MUNKIPATH}postflight.d/munkireport.py"
rm -f "${MUNKIPATH}preflight.d/munkireport.py"

# Remove munkireport.plist file
rm -f "${MUNKIPATH}postflight.d/cache/munkireport.plist"
rm -f "${MUNKIPATH}preflight.d/cache/munkireport.plist"
