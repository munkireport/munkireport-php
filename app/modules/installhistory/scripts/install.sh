#!/bin/bash

# Add InstallHistory (in 10.5 you need SWU.log)

# Get major OS version (uses uname -r and bash substitution)
# osvers is 10 for 10.6, 11 for 10.7, 12 for 10.8, etc.
osversionlong=$(uname -r)
osvers=${osversionlong/.*/}

if (( $osvers < 10 )); then 
	IPATH="/Library/Logs/Software Update.log"
else
	IPATH="/Library/Receipts/InstallHistory.plist"
fi

defaults write "${PREFPATH}" ReportItems -dict-add installhistory_model "${IPATH}"
