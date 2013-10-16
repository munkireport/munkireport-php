#!/bin/bash

echo 'Install managed install reporting'

defaults write "${PREFPATH}" ReportItems -dict-add Munkireport "/Library/Managed Installs/ManagedInstallReport.plist"
