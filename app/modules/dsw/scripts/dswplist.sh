#!/bin/bash

DSPREF="/Library/Preferences/DeployStudioInfo"

# Make sure pref file exists
defaults read "${DSPREF}" > /dev/null 2>&1 || defaults write "${DSPREF}" ds_workflow "No DeployStudio file added."
