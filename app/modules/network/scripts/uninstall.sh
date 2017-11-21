#!/bin/bash

# Remove networkinfo script
# Includes removal of previous shell and current python script for legacy purposes
rm -f "${MUNKIPATH}preflight.d/networkinfo.sh"
rm -f "${MUNKIPATH}preflight.d/networkinfo.py"

# Remove networkinfo.txt
rm -f "${CACHEPATH}networkinfo.txt"

