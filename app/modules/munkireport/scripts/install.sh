#!/bin/bash

defaults write "${PREFPATH}" ReportItems -dict-add munkireport "/Library/Managed Installs/ManagedInstallReport.plist"
