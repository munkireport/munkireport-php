#!/bin/bash

# Find out where the munki directory is so we can configure accordingly.
munki_install_dir=$(/usr/bin/python -c 'import CoreFoundation; print CoreFoundation.CFPreferencesCopyAppValue("ManagedInstallDir", "ManagedInstalls")')
munki_install_dir=$(echo ${munki_install_dir} | sed 's/\/$//')

# make sure the munki directory is defined. If not default back to normal
if [[ "${munki_install_dir}" == "None" ]]; then
    #Default back to normal since munki doesn't appear to be installed
    setreportpref "munkireport" '/Library/Managed Installs/ManagedInstallReport.plist'
else
    # configure munkireport to use munki's config
    setreportpref "munkireport" "${munki_install_dir}/ManagedInstallReport.plist"
fi
