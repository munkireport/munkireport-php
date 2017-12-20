#!/bin/bash

<<<<<<< HEAD
setreportpref "filevault_escrow" "/private/var/root/crypt_output.plist"
=======
# crypt output file
CRYPT_OUTPUT_PLIST="/var/root/crypt_output.plist"

# Set preference to include this file in the preflight check
setreportpref "filevault_escrow" "${CRYPT_OUTPUT_PLIST}"
>>>>>>> feature/illuminate_database_schema
