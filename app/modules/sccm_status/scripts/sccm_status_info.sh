#!/bin/bash

#####################################################################
#                                                                   #
# Author:  Calum Hunter                                             #
# Date:    17-10-2014                                               #
# Version  0.1                                                      #
# Purpose: Script to capture the status of the SCCM Agent           #
#          and send this to our Munki Report server                 #
#                                                                   #
#####################################################################

# Skip manual check
#if [ "$1" = 'manualcheck' ]; then
#	echo 'Manual check: skipping'
#	exit 0
#fi

# Create cache dir if it does not exit
DIR=$(dirname $0)
mkdir -p "$DIR/cache"

# Location of our output
sccm_file="$DIR/cache/sccm_status.txt"

# Location of the SCCM Plist
ccmpref="com.microsoft.ccmclient"

# Lets get the enrollment status
status=`defaults read $ccmpref EnrollmentStatus 2>/dev/null || echo 0`

getmoreinfo() # If we are enrolled, lets pull some more info
{
echo "Status = Enrolled"

# Get the Management point
mp=`defaults read $ccmpref MP`
echo "Management_Point = $mp"

# Get the user name of the user who enrolled device
enrolluser=`defaults read $ccmpref EnrollmentUserName`
echo "Enrollment_User_Name = $enrolluser"

# Get the Enrollment Server Address
enrollserver=`defaults read $ccmpref EnrollmentServerName`
echo "Enrollment_Server_Address = $enrollserver"

# Get the last Check in time
checkin=`defaults read $ccmpref OMALastConnectTime`
echo "Last_Check_In_Time = $checkin"

# Get the Certificate Expiration date
certexp=`defaults read $ccmpref CertExpDate`
echo "Client_Certificate_Expiry_Date = $certexp"
}

# Determine if we are enrolled
if [ "$status" = "0" ]; 
	then
	echo "Status = Not Enrolled" > "$sccm_file"
elif [ "$status" = "1" ];
	then
	getmoreinfo > "$sccm_file"
fi 

exit 0
