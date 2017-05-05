#!/usr/bin/env python

# This is the clientside module for mbbr_status

import os
import subprocess
import StringIO
import plistlib
import sys

def get_mbbr_info():
    '''Uses mbbr to get Malwarebytes activation status for this machine'''
    cmd = ['/usr/local/bin/mbbr register']
    rundata = subprocess.check_output(cmd, shell=True)
    try:
        buf = StringIO.StringIO(rundata)
        keylist = buf.read().replace('\t', '').splitlines()
        return keylist
    except Exception:
        return {}

def flatten_mbbr_info(dict):
    '''Flatten the output into a dictionary'''
    mbbrdata = {}
    for i in dict:
        if i:
            pair = i.split(':', 1)
            if len(pair) < 2:
                continue
            key, value = pair
            mbbrdata[key] = value
    return mbbrdata

def main():
    '''Main'''
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Check for existance of /usr/local/bin/mbbr before going further
    mbbr_dir = '/usr/local/bin/mbbr'
    if not os.path.exists(mbbr_dir):
        print 'Client is missing the mbbr tool at /usr/local/bin/mbbr. Exiting'
        exit(0)

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)

    # Get results
    result = dict()
    info = get_mbbr_info()
    result = flatten_mbbr_info(info)

    # Write mbbr results to cache
    output_plist = os.path.join(cachedir, 'malwarebytes.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
