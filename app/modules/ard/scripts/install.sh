#!/bin/bash

# Add ARD Preferences to munkireport
defaults write "${PREFPATH}" ReportItems -dict-add ard_model "/Library/Preferences/com.apple.RemoteDesktop.plist"
