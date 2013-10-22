#!/bin/bash

defaults write "${PREFPATH}" ReportItems -dict-add InventoryItem "/Library/Managed Installs/ApplicationInventory.plist"
