#!/bin/bash

# Script to create a new migration

DIR=$(dirname $0)
MODULE_PATH="${1}"
MODULE=$(basename $1)
MIGRATIONS_DIR="${MODULE_PATH}/migrations/"

mkdir -p "${MIGRATIONS_DIR}"

# Copy migration
MIGRATION_FILENAME=$(date "+%Y_%m_%d_%H%M%S")"_${MODULE}.php"
cat "${DIR}/templates/migration.php" | sed "s/MODULE/${MODULE}/g" > "${MIGRATIONS_DIR}${MIGRATION_FILENAME}"
