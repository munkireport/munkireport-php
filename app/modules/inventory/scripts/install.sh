#!/bin/bash
###############
# modified by Wesley Whetstone to dynamically support Munki Install Directories.
###############

# inventory controller
DR_CTL="${BASEURL}index.php?/module/inventory/"

# Find out where the munki directory is to set accordingly.
munki_install_dir=$(/usr/bin/defaults read /Library/Preferences/ManagedInstalls ManagedInstallDir)

# Get the scripts in the proper directories
${CURL} "${DR_CTL}get_script/inventory_add_plugins" -o "${MUNKIPATH}postflight.d/inventory_add_plugins.py"

# Check exit status of curl
if [ $? = 0 ]; then
  # Make executable
  chmod a+x "${MUNKIPATH}postflight.d/inventory_add_plugins.py"

  # make sure the munki install directory is defined. If not default back to normal
  if [[ "${munki_install_dir}" == "" ]]; then
    setreportpref "munkireport" '/Library/Managed Installs/ApplicationInventory.plist'
  fi
  # Set preference to include this file in the preflight check
  setreportpref "inventory" "${munki_install_dir}/ApplicationInventory.plist"

else
  echo "Failed to download all required components!"
  rm -f "${MUNKIPATH}postflight.d/inventory_add_plugins.py"
  ERR=1
fi