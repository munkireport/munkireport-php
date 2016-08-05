#!/usr/bin/python
"""
extracts information about TimeMachine configuration and logs
"""

import sys
import os
import subprocess
import plistlib

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)

# Create cache dir if it does not exist
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

# Run tmutil destinationinfo -X and capture the xml output to parse
sp = subprocess.Popen(['tmutil', 'destinationinfo', '-X'], stdout=subprocess.PIPE)
out, err = sp.communicate()

plist = plistlib.readPlistFromString(out)
destinations = plist['Destinations']
result = ''

# Examine destinations for information.  May have multiple destinations.
for destination in destinations:
    if destination.get('LastDestination') == 1:
        if destination.get('Kind') == "Network":
            result += "TM_LOCATION: " + destination['URL'] + '\n'
        elif destination.get('Kind') == "Local":
            result += "TM_LOCATION: " + destination['MountPoint'] + '\n'
        else:
            result += "TM_LOCATION: UNKNOWN" + '\n'

        result += "TM_KIND: " + destination['Kind'] + '\n'
        result += "TM_NAME: " + destination['Name'] + '\n'

# Store 7 days of relevant syslog events
logproc = subprocess.Popen(['syslog', '-F', '$((Time)(utc)) $Message', '-k', 'Sender', 'com.apple.backupd', '-k', 'Time', 'ge', '-7d', '-k', 'Message', 'R', '^(Backup|Starting).*'], stdout=subprocess.PIPE)
out, err = logproc.communicate()
result += '\n' + out + '\n'


# Write to disk
txtfile = open("%s/timemachine.txt" % cachedir, "w")
txtfile.write(result.encode('utf-8'))
txtfile.close()

exit(0)









