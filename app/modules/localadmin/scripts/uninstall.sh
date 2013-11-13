#!/bin/bash

# Remove localadmin script
rm -f "${MUNKIPATH}preflight.d/localadmin"

# Remove localadmins.txt file
rm -f "${MUNKIPATH}preflight.d/cache/localadmins.txt"
