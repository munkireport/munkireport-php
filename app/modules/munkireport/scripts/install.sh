#!/bin/bash
###############
# modified by Wesley Whetstone to dynamically support Munki Install Directories.
###############

# Find out where the munki directory is to set accordingly.
munki_install_dir=$(/usr/bin/python -c 'import CoreFoundation; print CoreFoundation.CFPreferencesCopyAppValue("ManagedInstallDir", "ManagedInstalls")')
munki_install_dir_len=$((${#munki_install_dir}-1))

#check if the format of the munki_install_dir is correct.
if [[ ${munki_install_dir:$munki_install_dir_len:1} == '/' ]]; then
  munki_install_dir=$(echo ${munki_install_dir} | sed 's/.$//')
fi
# make sure the munki directory is defined. If not default back to normal
if [[ "${munki_install_dir}" == "None" ]]; then
  #Default back to normal since munki doesn't appear to be installed
  setreportpref "munkireport" '/Library/Managed Installs/ManagedInstallReport.plist'
else
  # configure munkireport to use munki's config
  setreportpref "munkireport" "${munki_install_dir}/ManagedInstallReport.plist"
fi
