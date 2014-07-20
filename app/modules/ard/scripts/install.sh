#!/bin/bash

ARDPREF='/Library/Preferences/com.apple.RemoteDesktop'

# Make sure ard pref exists
defaults read "$ARDPREF" > /dev/null 2>&1 || defaults write "$ARDPREF" MR created

# Add ARD Preferences to munkireport
defaults write "${PREFPATH}" ReportItems -dict-add ard_model "$ARDPREF"
