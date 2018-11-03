#!/usr/bin/python

import subprocess
import json
import sys
import os
import plistlib
import dateutil.parser as dp

def dict_clean(items):
    result = {}
    for key, value in items:
        if value is None:
            value = 'None'
        result[key] = value
    return result

def main():
    s1_binary = '/Library/Sentinel/sentinel-agent.bundle/Contents/MacOS/sentinelctl'

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)

    if os.path.isfile(s1_binary):
        # Create cache dir if it does not exist
        cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
        if not os.path.exists(cachedir):
            os.makedirs(cachedir)

        summary_command = [s1_binary, 'summary', 'json']
        task = subprocess.Popen(summary_command,
                                stdout=subprocess.PIPE,
                                stderr=subprocess.PIPE)

        (stdout, stderr) = task.communicate()
        # Sentinel One's output has a header of "Summary information" that needs to be stripped off to be proper json
        s1_summary = json.loads(stdout.split('\n',1)[1], object_pairs_hook=dict_clean)
        # convert the ISO time to epoch time and store back in the variable
        s1_summary['last-seen'] = dp.parse(s1_summary['last-seen']).strftime('%s')

        # Write to disk
        output_plist = os.path.join(cachedir, 'sentinelone.plist')
        plistlib.writePlist(s1_summary, output_plist)
    else:
        print "SentinelOne's sentinelctl binary missing. Exiting."
        exit(0)

if __name__ == "__main__":
    main()
