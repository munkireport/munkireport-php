#!/bin/bash

# inventory controller
DR_CTL="${BASEURL}index.php?/module/inventory/"

# Find out where the munki directory is to set accordingly.
munki_install_dir=$(/usr/bin/python -c 'import CoreFoundation; print CoreFoundation.CFPreferencesCopyAppValue("ManagedInstallDir", "ManagedInstalls")')
munki_install_dir=$(echo ${munki_install_dir} | sed 's/\/$//')

# Get the scripts in the proper directories
"${CURL[@]}" "${DR_CTL}get_script/inventory_add_plugins" -o "${MUNKIPATH}postflight.d/inventory_add_plugins.py"

# Check exit status of curl
if [ $? = 0 ]; then
    # Make executable
    chmod a+x "${MUNKIPATH}postflight.d/inventory_add_plugins.py"
    # make sure the munki install directory is defined. If not default back to normal
    if [[ "${munki_install_dir}" == "None" ]]; then
        # This also intended behavior if munki isn't installed
        setreportpref "inventory" '/Library/Managed Installs/ApplicationInventory.plist'
    else
        # Set preference to include this file in the preflight check
        setreportpref "inventory" "${munki_install_dir}/ApplicationInventory.plist"
    fi
else
    echo "Failed to download all required components!"
    rm -f "${MUNKIPATH}postflight.d/inventory_add_plugins.py"
    ERR=1
fi
