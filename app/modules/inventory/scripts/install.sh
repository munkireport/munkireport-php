#!/bin/bash

echo 'Install inventory reporting'

defaults write "${PREFPATH}" ReportItems -dict-add InventoryItem "/Library/Managed Installs/ApplicationInventory.plist"
