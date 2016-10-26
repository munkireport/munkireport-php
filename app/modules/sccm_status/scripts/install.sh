#!/bin/bash

# bluetooth controller
CTL="${BASEURL}index.php?/module/sccm_status/"

# Get the scripts in the proper directories
${CURL} "${CTL}get_script/sccm_status_info.sh" -o "${MUNKIPATH}preflight.d/sccm_status_info.sh"

# Check exit status of curl
if [ $? = 0 ]; then
        # Make executable
        chmod a+x "${MUNKIPATH}preflight.d/sccm_status_info.sh"

        # Set preference to include this file in the preflight check
        setreportpref "sccm_status" "${CACHEPATH}sccm_status.txt"

else
        echo "Failed to download all required components!"
        rm -f "${MUNKIPATH}preflight.d/sccm_status_info.sh"

        # Signal that we had an error
        ERR=1
fi
