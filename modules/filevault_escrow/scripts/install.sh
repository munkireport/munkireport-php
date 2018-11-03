#!/bin/bash

# crypt output file
CRYPT_OUTPUT_PLIST="/var/root/crypt_output.plist"

# Set preference to include this file in the preflight check
setreportpref "filevault_escrow" "${CRYPT_OUTPUT_PLIST}"
