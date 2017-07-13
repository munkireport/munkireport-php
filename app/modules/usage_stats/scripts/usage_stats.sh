#!/bin/bash

if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
usage_stats_file="$DIR/cache/usage_stats.plist"
usage_stats_temp_file="$DIR/cache/usage_stats_temp.plist"
usage_stats_temp2_file="$DIR/cache/usage_stats_temp2.plist"

# Gather historical information
/usr/bin/powermetrics --show-initial-usage -s network,disk -u "${usage_stats_temp_file}" -f plist -n 0 2>/dev/null

# Gather usage information
/usr/bin/powermetrics -s cpu_power,gpu_power,thermal,battery -u "${usage_stats_temp2_file}" -f plist -n 1 2>/dev/null

# Merge plist files
cat "${usage_stats_temp2_file}" >> "${usage_stats_temp_file}"

# Flatten
tr -d "\n" < "${usage_stats_temp_file}" > "${usage_stats_file}"

# Remove non-ascii charactors
sed -i '' 's/[^[:print:]]//g' "${usage_stats_file}"

# Condense plist into one
sed -i '' 's/<\/dict><\/plist><?xml version="1.0" encoding="UTF-8"?><!DOCTYPE plist PUBLIC "-\/\/Apple\/\/DTD PLIST 1.0\/\/EN" "http:\/\/www.apple.com\/DTDs\/PropertyList-1.0.dtd"><plist version="1.0"><dict>//g' "${usage_stats_file}"

# Remove temp files
rm "${usage_stats_temp_file}"
rm "${usage_stats_temp2_file}"

exit 0