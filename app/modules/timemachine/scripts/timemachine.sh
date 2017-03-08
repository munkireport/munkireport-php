#!/bin/bash

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

DIR=$(dirname $0)

mkdir -p "$DIR/cache"

# OS check
OSVERSION=$(/usr/bin/sw_vers -productVersion | /usr/bin/cut -d . -f 2)

if [[ ${OSVERSION} -lt 12 ]]; then

# Store 7 days of relevant syslog events
syslog -F '$((Time)(utc)) $Message' -k Sender com.apple.backupd -k Time ge -7d -k Message R '^(Backup|Starting).*' > "$DIR/cache/timemachine.txt" 

else
# 10.12+

#log show --last 7d --predicate 'subsystem == "com.apple.TimeMachine"' --info | grep 'upd: (' | cut -c 1-19,140-999

# Command is disabled because it stresses the system and times out
# Mostly because Apple's new fancy log system is not very good

fi