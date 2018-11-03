#!/bin/bash

# Remove mbbr_status script
rm -f "${MUNKIPATH}preflight.d/mbbr_status.py"

# Remove malwarebytes.plist file
rm -f "${CACHEPATH}malwarebytes.plist"
