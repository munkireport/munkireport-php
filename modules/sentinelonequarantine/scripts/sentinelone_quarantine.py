#!/usr/bin/python

import subprocess
import json
import sys
import os
import plistlib
import dateutil.parser as dp
import operator

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

        # Check if any files are in quarantine
        
        quarantine_command = [s1_binary, 'quarantine', 'list', 'files']
        task = subprocess.Popen(quarantine_command,
                                stdout=subprocess.PIPE,
                                stderr=subprocess.PIPE)

        (q_stdout, q_stderr) = task.communicate()
 
        quarantine_list = []
        if "No files quarantined" in q_stdout:
            pass 
        else:
            lines = q_stdout.splitlines()
            mylist = [list(thing.split(' ')) for thing in lines]

            # recombine file path since it split at spaces
            for items in mylist:
                items[3:] = [' '.join(items[3:])]

            sub_q_dict = {}
        #iterate thru the list of lists
            for i in range(len(mylist)):
                uuid,path=operator.itemgetter(1,3)(mylist[i])
                sub_q_dict["uuid"] = uuid
                sub_q_dict["path"] = path
                quarantine_list.append(sub_q_dict.copy())

        # Write array of dicts to disk
        q_output_plist = os.path.join(cachedir, 'sentinelone_quarantine.plist')
        plistlib.writePlist(quarantine_list, q_output_plist)

    else:
        print "SentinelOne's sentinelctl binary not found. Exiting."
        exit(0)

if __name__ == "__main__":
    main()


