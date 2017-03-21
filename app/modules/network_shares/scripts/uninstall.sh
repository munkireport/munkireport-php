#!/bin/bash

# Remove network_shares script
rm -f "${MUNKIPATH}preflight.d/network_shares.py"

# Remove network_shares.plist file
rm -f "${CACHEPATH}network_shares.plist"
