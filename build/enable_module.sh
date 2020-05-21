#!/bin/sh

#### Arg Check 
if [ -z "$1" ]; then
        echo 'You must pass in a module to enable. Example: "enable_module.sh tuxudo/thunderbolt"'
        exit 0
fi

#### Variable assignments
SCRIPT_DIRECTORY="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
PARENT_DIRECTORY=$(dirname "$SCRIPT_DIRECTORY")
MODULE="$1"
ENV="$PARENT_DIRECTORY/.env"
COMPOSER_PATH=$(command -v composer)
MODULE_BASE=$(echo "$MODULE" | cut -d/ -f2)
MODULES=$(grep -i "MODULES=" "$ENV")
LINE_NUMBER=$(grep -ni "MODULES=" "$ENV" | cut -f1 -d:)

#### Check for Composer
if [ ! "$COMPOSER_PATH" ]; then
    EXPECTED_CHECKSUM="$(curl --silent https://composer.github.io/installer.sig)"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        >&2 echo 'ERROR: Invalid installer checksum'
        exit 1
    fi

    php composer-setup.php --filename composer
    rm composer-setup.php
    COMPOSER_PATH="$PARENT_DIRECTORY/composer"
fi

#### Install Module via Composer
COMPOSER=composer.local.json "$COMPOSER_PATH" require "$MODULE" 

if [ $? -eq 1 ]; then
    echo "$MODULE not found"
    exit
fi

#### Run Composer and Database Migration
"$COMPOSER_PATH" update "$MODULE" --no-dev
php "$PARENT_DIRECTORY/database/migrate.php"

#### Clean up
cp -Rf "$PARENT_DIRECTORY/vendor/$MODULE" "$PARENT_DIRECTORY/local/modules/"
COMPOSER=composer.local.json composer remove "$MODULE"
"$COMPOSER_PATH" update "$MODULE" --no-dev

#### Add Module to .env
if [ ! "$MODULES" ]; then
        echo "Modules not configured. Configuring base modules"
        echo "MODULES='munkireport, managedinstalls, disk_report, $MODULE_BASE'" >> "$PARENT_DIRECTORY/.env"
    else
        if [[ "$MODULES" == *"$MODULE_BASE"* ]]; then
                echo "Module already enabled"
            else
                sed -i.bak -e "${LINE_NUMBER}s/,/, ${MODULE_BASE},/" "$ENV"
        fi
fi
