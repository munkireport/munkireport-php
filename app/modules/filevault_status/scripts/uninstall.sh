#!/bin/bash

# Remove filevaultstatus script
rm -f "${MUNKIPATH}preflight.d/filevaultstatus"

# Remove the filevaultstatus.txt cache file
rm -f "${MUNKIPATH}preflight.d/cache/filevaultstatus.txt"

# We'll leave the check script alone
# rm -f /usr/local/bin/filevault_2_status_check.sh
