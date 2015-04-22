#!/bin/bash

MODULE_NAME="certificate"
MODULESCRIPT="cert_check"
MODULE_CACHE_FILE="certificate.txt"

# Remove preflight script
rm -f "${MUNKIPATH}preflight.d/${MODULESCRIPT}"

# Remove cache file
rm -f "${CACHEPATH}${MODULE_CACHE_FILE}"
