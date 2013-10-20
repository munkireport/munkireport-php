#!/bin/bash

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
networkfile="$DIR/cache/networkinfo.txt"

echo "Storing network information in $networkfile"

# Store network information in network.txt
networkservices=$(networksetup -listallnetworkservices | grep -v asterisk)

# Truncate file
> "$networkfile"

IFS=$'\n'
for service in ${networkservices[@]}
do
  service=$(echo $service | tr -d '*')
  echo "Service: $service" >> "$networkfile"
  networksetup -getinfo "$service" >> "$networkfile"
done

