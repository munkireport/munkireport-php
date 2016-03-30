#!/bin/sh

# FindMyMac information

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
findmymac_manfiest_file="$DIR/cache/findmymac.txt"

#### FindMyMac Manifest Information ####
fmmdata=$(/usr/sbin/nvram -p | grep fmm-mobileme-token-FMM)
if [[ -z $fmmdata ]]
then
    echo "Status = Disabled" > "$findmymac_manfiest_file"
else
    echo "Status = Enabled" > "$findmymac_manfiest_file"
    echo "Data = $fmmdata" >> "$findmymac_manfiest_file"
    
    email=$(grep -i -o '[A-Z0-9._+-]\+@[A-Z0-9.-]\+\.[A-Z]\{2,4\}' "$findmymac_manfiest_file" | cut -c 3-)
    echo "Email = $email" >> "$findmymac_manfiest_file"
fi

exit 0
