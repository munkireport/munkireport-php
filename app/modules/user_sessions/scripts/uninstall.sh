#!/bin/bash

# Remove user_sessions script
rm -f "${MUNKIPATH}preflight.d/user_sessions.py"

# Remove network_shares.plist file
rm -f "${CACHEPATH}user_sessions.plist"
