#!/bin/bash

echo 'Install InstallHistory report'

# Add InstallHistory (in 10.5 you need SWU.log)
if [[ `uname -r` < '10.0.0' ]]; then 
	IPATH="/Library/Logs/Software Update.log"
else
	IPATH="/Library/Receipts/InstallHistory.plist"
fi

defaults write "${PREFPATH}" ReportItems -dict-add InstallHistory "${IPATH}"
