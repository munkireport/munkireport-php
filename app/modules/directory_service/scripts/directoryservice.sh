#!/bin/bash 

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

DIR=$(dirname $0)

mkdir -p "$DIR/cache"

DS=''
AD_COMMENTS=''

# Find which Directory Service we are bound to
DS=`/usr/bin/dscl localhost -list . | head -n 1`
if [ "${DS}" = "Local" ]; then
	DS="Not bound to any server"
fi
	
# If AD, read Comments Field in AD
if [ "${DS}" = "Active Directory" ]; then

	osversionlong=`sw_vers -productVersion`
	osvers=${osversionlong:3:1}
	localhostname=`/usr/sbin/scutil --get LocalHostName`
	# Set variable for Domain
	domain=

	if [[ ${osvers} -ge 7 ]]; then
		AD_COMMENTS=`dscl /Active\ Directory/$domain/All\ Domains/ -read Computers/$localhostname$ Comment | tr -d '\n' | awk '{$1 =""; print }' `
	fi
else
	if [ "${osvers}" = 6 ]; then
		AD_COMMENTS=`dscl /Active\ Directory/All\ Domains/ -read Computers/$localhostname$ Comment | tr -d '\n' | awk '{$1 =""; print }'`	
	fi
fi


printf '%s\n' "$DS" "$AD_COMMENTS" > "$DIR/cache/directoryservice.txt"


