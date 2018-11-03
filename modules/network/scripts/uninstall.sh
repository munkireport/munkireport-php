#!/bin/bash

# Remove networkinfo script
rm -f "${MUNKIPATH}preflight.d/networkinfo.sh"
rm -f "${MUNKIPATH}preflight.d/networkinfo.py"

# Remove networkinfo.txt
rm -f "${CACHEPATH}networkinfo.txt"

