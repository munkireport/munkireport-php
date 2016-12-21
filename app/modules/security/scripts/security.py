#!/usr/bin/python
"""
Extracts information about SIP, Gatekeeper, and users who have remote access.
"""

import os
import sys
import subprocess
import plistlib

def gatekeeper_check():
    """ Gatekeeper checks. Simply calls the spctl and parses status.
    No need for OS checks because we know we are 10.7+"""

    sp = subprocess.Popen(['spctl', '--status'], stdout=subprocess.PIPE)
    out, err = sp.communicate()
    return out.split()[1]


def sip_check():
    """ SIP checks. We need to be running 10.11 or newer."""

    if float(os.uname()[2][0:2]) >= 15:
        sp = subprocess.Popen(['csrutil', 'status'], stdout=subprocess.PIPE)
        out, err = sp.communicate()
        return out.split()[4].strip('.')
    else:
        return out.split()[4].strip('.')

def main():
    """main"""

    # Skip running on manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)

    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Create an empty directory object to hold results from check methods, then run.
    result = {}
    result.update({'gatekeeper': gatekeeper_check()})
    result.update({'sip': sip_check()})

    # Write results of checks to cache file
    output_plist = os.path.join(cachedir, 'security.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
