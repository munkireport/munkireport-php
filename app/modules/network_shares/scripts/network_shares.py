#!/usr/bin/python

"""
network shares for munkireport.
"""

import subprocess
import os
import plistlib
import sys


def get_network_shares():
    '''Uses system profiler to get network shares for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPNetworkVolumeDataType', '-xml']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    try:
        plist = plistlib.readPlistFromString(output)
        # system_profiler xml is an array
        sp_dict = plist[0]
        items = sp_dict['_items']
        return items
    except Exception:
        return {}

def flatten_network_shares(array):
    '''Un-nest network shares, return array with objects with relevant keys'''
    out = []
    for obj in array:
        device = {'name': '', 'automounted': 0,}
        for item in obj:
            if item == '_items':
                out = out + flatten_network_shares(obj['_items'])
            elif item == '_name':
                device['name'] = obj[item]
            elif item == 'spnetworkvolume_mntfromname':
                device['mntfromname'] = obj[item]
            elif item == 'spnetworkvolume_fstypename':
                device['fstypename'] = obj[item]
            elif item == 'spnetworkvolume_fsmtnonname':
                device['fsmtnonname'] = obj[item]
            elif item == 'spnetworkvolume_automounted' and obj[item] == 'Yes':
                device['automounted'] = 1
        out.append(device)
    return out
    

def main():
    """Main"""
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)

    # Get results
    result = dict()
    info = get_network_shares()
    result = flatten_network_shares(info)
    
    # Write network_shares results to cache
    output_plist = os.path.join(cachedir, 'network_shares.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
