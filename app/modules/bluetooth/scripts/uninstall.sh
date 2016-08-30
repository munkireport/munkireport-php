#!/bin/bash

# Remove bluetooth script
rm -f "${MUNKIPATH}preflight.d/bluetooth.{sh,py}"

# Remove bluetoothinfo.txt file
rm -f "${CACHEPATH}bluetoothinfo.{txt,plist}"
