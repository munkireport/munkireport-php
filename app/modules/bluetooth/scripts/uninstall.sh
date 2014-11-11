#!/bin/bash

# Remove bluetooth script
rm -f "${MUNKIPATH}preflight.d/bluetooth.sh"

# Remove bluetoothinfo.txt file
rm -f "${MUNKIPATH}preflight.d/cache/bluetoothinfo.txt"
