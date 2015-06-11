#!/bin/bash

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

DIR=$(dirname $0)

mkdir -p "$DIR/cache"

# Store 7 days of relevant syslog events
syslog -F '$((Time)(utc)) $Message' -k Sender com.apple.backupd -k Time ge -7d -k Message R '^(Backup|Starting).*' > "$DIR/cache/timemachine.txt" 
