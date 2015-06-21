#!/bin/bash

# Remove servermetrics script
rm -f "${MUNKIPATH}preflight.d/servermetrics.py"

# Remove servermetrics.json file
rm -f "${MUNKIPATH}preflight.d/cache/servermetrics.json"

# Keep ccl_asldb.py as it may be used by other scripts in the future
# rm -f ${MUNKIPATH}munkireportlib/ccl_asldb.py