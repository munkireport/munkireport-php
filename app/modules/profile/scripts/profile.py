#!/usr/bin/python
"""
extracts information about the profiles from system profiler
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

sp = subprocess.Popen(['system_profiler', '-xml', 'SPConfigurationProfileDataType'], stdout=subprocess.PIPE)
out, err = sp.communicate()

result = ''

# If not profiles are installed - write 0 and exit. 
if not out:
	result = ''
	# Write to disk
	txtfile = open("%s/profile.txt" % cachedir, "w")
	txtfile.write(result)
	txtfile.close()
	exit(0)

plist = plistlib.readPlistFromString(out)

#loop inside each graphic card
for profiles in plist[0]['_items']:
	# add number of profiles installed to result
	for profile in profiles.get('_items'):
		for payload in profile.get('_items'):
			result += 'ProfileUUID = ' + profile.get('spconfigprofile_profile_uuid') + '\n'
			result += 'ProfileName = ' + profile.get('_name') + '\n'
			result += 'ProfileRemovalDisallowed = ' + str(profile.get('spconfigprofile_RemovalDisallowed')) + '\n'
			result += 'PayloadName = ' + payload.get('_name') + '\n'
			result += 'PayloadDisplayName = ' + payload.get('spconfigprofile_payload_display_name') + '\n'
			result += 'PayloadData = test\n'
			#result += 'PayloadData = ' + str(payload.get('spconfigprofile_payload_data')) + '\n'
			result += "---------\n"
		#result += '' + profile.get('_name') + '\n'
		



##############

# Write to disk
txtfile = open("%s/profile.txt" % cachedir, "w")
txtfile.write(result)
txtfile.close()

#exit(0)
