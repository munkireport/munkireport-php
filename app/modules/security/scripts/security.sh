#!/bin/bash

if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"

osvers=$(sw_vers -productVersion | awk -F. '{print $2}')

# Checks Gatekeeper status on Macs running 10.7 or higher

if [[ ${osvers} -ge 7 ]]; then
    gatekeeper_status=$(/usr/sbin/spctl --status | grep "assessments" | cut -c13-)
   if [ "$gatekeeper_status" = "disabled" ]; then
      gatekeeper=Disabled
   else
      gatekeeper=Active
   fi
else 
 	  gatekeeper="Not Supported"
#   echo "Gatekeeper is $gatekeeper"
fi

 
# Checks SIP status on Macs running 10.11 or higher

if [[ ${osvers} -ge 11 ]]; then
   sip_status=$(/usr/bin/csrutil status | awk '{print $5}')
   if [ "$sip_status" == "disabled." ]; then
      sip=Disabled
   else
      sip=Active
   fi
else 
	  sip="Not Supported"
#   echo "SIP is $sip_status"
fi

echo -e "Gatekeeper: ${gatekeeper}\n""SIP: ${sip}"> "$DIR/cache/security.txt"