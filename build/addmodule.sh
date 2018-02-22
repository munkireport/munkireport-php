#!/bin/bash

# Script to create a new module structure

DIR=$(dirname $0)
MODULE_PATH="${1}"
MODULE=$(basename $1)
SCRIPT_DIR="${MODULE_PATH}/scripts/"
VIEW_DIR="${MODULE_PATH}/views/"
LOCALE_DIR="${MODULE_PATH}/locales/"

# Create directories
mkdir "${MODULE_PATH}" || exit 1
mkdir "${SCRIPT_DIR}" || exit 1
mkdir "${VIEW_DIR}" || exit 1
mkdir "${LOCALE_DIR}" || exit 1

# Copy provides.php
cat "${DIR}/templates/provides.php" | sed "s/MODULE/${MODULE}/g" > "${MODULE_PATH}/provides.php"

# Copy install scripts from template
cat "${DIR}/templates/install.sh" | sed "s/MODULE/${MODULE}/g" > "${SCRIPT_DIR}install.sh"
cat "${DIR}/templates/uninstall.sh" | sed "s/MODULE/${MODULE}/g" > "${SCRIPT_DIR}uninstall.sh"
cat "${DIR}/templates/module_script.sh" | sed "s/MODULE/${MODULE}/g" > "${SCRIPT_DIR}${MODULE}.sh"

# Copy php templates
cat "${DIR}/templates/controller.php" | sed "s/MODULE/${MODULE}/g" > "${MODULE_PATH}/${MODULE}_controller.php"
cat "${DIR}/templates/model.php" | sed "s/MODULE/${MODULE}/g" > "${MODULE_PATH}/${MODULE}_model.php"

# Copy views
cat "${DIR}/templates/listing.php" | sed "s/MODULE/${MODULE}/g" > "${VIEW_DIR}${MODULE}_listing.php"
cat "${DIR}/templates/report.php" | sed "s/MODULE/${MODULE}/g" > "${VIEW_DIR}${MODULE}_report.php"
cat "${DIR}/templates/widget.php" | sed "s/MODULE/${MODULE}/g" > "${VIEW_DIR}${MODULE}_widget.php"

# Copy locale template
cat "${DIR}/templates/en.json" | sed "s/MODULE/${MODULE}/g" > "${LOCALE_DIR}en.json"

# Copy migration
${DIR}/addmigration.sh "${MODULE}"

echo "** Created module ${MODULE} **"

find "${MODULE_PATH}"
