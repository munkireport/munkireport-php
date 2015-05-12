#!/bin/bash

DSPREF='/Library/Preferences/DeployStudioInfo'

# Make sure pref file exists
defaults read "$DSPREF" > /dev/null 2>&1 || defaults write "$DSPREF" ds_workflow "No DeployStudio file added."

# Add preferences to munkireport
defaults write "${PREFPATH}" ReportItems -dict-add ard_model "${DSPREF}.plist"
