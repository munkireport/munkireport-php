#!/bin/bash

# Remove ciscoswitch script
rm -f "${MUNKIPATH}preflight.d/ciscoswitch"

# Remove networkswitch.txt file
rm -f "${MUNKIPATH}preflight.d/cache/networkswitch.txt"
