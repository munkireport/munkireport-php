#!/bin/bash
###############
# modified by Wesley Whetstone to dynamically support Munki Install Directories.
###############

# Find out where the munki directory is to set accordingly. 
munki_install_dir=$(/usr/bin/defaults read /Library/Preferences/ManagedInstalls ManagedInstallDir)

# make sure the munki directory is defined. If not default back to normal
if [[ "${munki_install_dir}" == "" ]]; then
  #Default back to normal since munki doesn't appear to be installed
  setreportpref "munkireport" '/Library/Managed Installs/ManagedInstallReport.plist'
fi

# configure munkireport to use munki's config
setreportpref "munkireport" "${munki_install_dir}/ManagedInstallReport.plist"
