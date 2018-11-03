#!/bin/bash

# Remove gpu script
rm -f "${MUNKIPATH}preflight.d/gpu.py"

# Remove gpuinfo.plist file
rm -f "${CACHEPATH}gpuinfo.plist"
