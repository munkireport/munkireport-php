#!/usr/bin/python
"""
extracts information about the profiles from system profiler
"""

import os

# Create cache dir if it does not exist
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

# Check if the OS even works with profiles
# 11.0 is equivalent to 10.7 Lion
# If it isn't - write empty cache file
if float(os.uname()[2][0:2]) < 11.0:
	result = ''
	print 'OS X not 10.7 or higher'
	# Write to disk
	txtfile = open("%s/profile.txt" % cachedir, "w")
	txtfile.write(result)
	txtfile.close()
	exit(0)

# If here - OS is Lion or higher and can safely import the rest.
import sys
import subprocess
import plistlib
import json

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)


sp = subprocess.Popen(['system_profiler', '-xml', 'SPConfigurationProfileDataType'], stdout=subprocess.PIPE)
out, err = sp.communicate()

result = ''

# If no profiles are installed - write 0 and exit. 
if not out:
	result = ''
	# Write to disk
	txtfile = open("%s/profile.txt" % cachedir, "w")
	txtfile.write(result)
	txtfile.close()
	exit(0)

plist = plistlib.readPlistFromString(out)

#loop through profile xml data
for profiles in plist[0]['_items']:
	# Sort the profile dictionary
	for profile in sorted(profiles.get('_items')):
		# Sort the payload dictionary
		for payload in sorted(profile.get('_items')):
			result += 'ProfileUUID = ' + profile.get('spconfigprofile_profile_uuid', 'No UUID') + '\n'
			result += 'ProfileName = ' + profile.get('_name', 'No Profile Name') + '\n'
			result += 'ProfileRemovalDisallowed = ' + str(profile.get('spconfigprofile_RemovalDisallowed')) + '\n'
			result += 'PayloadName = ' + payload.get('_name', 'No Payload Name') + '\n'
			result += 'PayloadDisplayName = ' + payload.get('spconfigprofile_payload_display_name', 'No Payload Display Name') + '\n'
			result += 'PayloadData = ' + json.dumps(payload.get('spconfigprofile_payload_data', 'No Payload Data')) + '\n'
			result += "---------\n"
			#result += '' + profile.get('_name') + '\n'

##############

# Write to disk
txtfile = open("%s/profile.txt" % cachedir, "w")
txtfile.write(result.encode('utf-8'))
txtfile.close()

#exit(0)
