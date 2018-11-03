#!/bin/sh
# Made by tuxudo

# homebrew installed bottles and stuff information

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Check if homebrew is installed
if [[ -f /usr/local/bin/brew ]]; then

    # Create cache dir if it does not exist
    DIR=$(dirname $0)
    mkdir -p "$DIR/cache"
    homebrewfile="$DIR/cache/homebrew.json"

    # First cd to / to get around non-existant directory error
    # The sudo is needed to escape brew.sh's UID of 0 check
    cd /; sudo -HE -u nobody /usr/local/bin/brew info --json=v1 --installed > "${homebrewfile}"

else

    echo "Homebrew is not installed, skipping"

fi
exit 0
