#!/bin/bash

# Remove fan_temps script
rm -f "${MUNKIPATH}preflight.d/fan_temps"

# Remove fan_temps.plist
rm -f "${CACHEPATH}fan_temps.plist"

# Remove smckit
rm -f "${MUNKIPATH}/smc"

