#!/bin/bash

# Script to create a new module structure

DIR=$(dirname $0)
MODULE="${1}"
MODULE_PATH="${DIR}/../app/modules/${MODULE}/"
SCRIPT_DIR="${MODULE_PATH}/scripts/"

# Create directories
mkdir "${MODULE_PATH}" || exit 1
mkdir "${SCRIPT_DIR}" || exit 1
mkdir "${MODULE_PATH}views" || exit 1

# Copy install scripts from template
cat "${DIR}/templates/install.sh" | sed "s/MODULE/${MODULE}/g" > "${SCRIPT_DIR}install.sh"
cat "${DIR}/templates/uninstall.sh" | sed "s/MODULE/${MODULE}/g" > "${SCRIPT_DIR}uninstall.sh"
cat "${DIR}/templates/module_script.sh" | sed "s/MODULE/${MODULE}/g" > "${SCRIPT_DIR}${MODULE}.sh"

# Copy php templates
cat "${DIR}/templates/controller.php" | sed "s/MODULE/${MODULE}/g" > "${MODULE_PATH}${MODULE}_controller.php"
cat "${DIR}/templates/model.php" | sed "s/MODULE/${MODULE}/g" > "${MODULE_PATH}${MODULE}_model.php"

echo "** Created module ${MODULE} **"

find "${MODULE_PATH}"