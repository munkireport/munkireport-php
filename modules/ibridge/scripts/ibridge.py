#!/usr/bin/python

"""
Devtools for munkireport.
Will return all details about iBridges in the machine
"""

import subprocess
import os
import plistlib
import sys
import platform
import re

def getOsVersion():
    """Returns the minor OS version."""
    os_version_tuple = platform.mac_ver()[0].split('.')
    return int(os_version_tuple[1])

def get_ibridge_info():
    '''Uses system profiler to get dev tools for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPiBridgeDataType', '-xml']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    try:
        plist = plistlib.readPlistFromString(output)
        # system_profiler xml is an array
        ibridge_dict = plist[0]
        items = ibridge_dict['_items']
        return items
    except Exception:
        return {}
    

def flatten_ibridge_info(array):
    '''Un-nest dev info, return array with objects with relevant keys'''
    out = []
    for obj in array:
        ibridge = {}
        for item in obj:
            if item == '_items':
                out = out + flatten_ibridge_info(obj['_items'])
            elif item == 'ibridge_boot_uuid':
                ibridge['boot_uuid'] = obj[item]
            elif item == 'ibridge_build':
                ibridge['build'] = obj[item]
            elif item == 'ibridge_model_identifier':
                ibridge['model_identifier'] = obj[item]
            elif item == 'ibridge_model_name':
                ibridge['model_name'] = obj[item]
            elif item == 'ibridge_serial_number':
                ibridge['ibridge_serial_number'] = obj[item]
           
        out.append(ibridge)
    return out
    

def main():
    """Main"""
    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)
    
    # Check OS version and skip if too old       
    if getOsVersion() < 12:
        print 'Skipping iBridge check, OS does not support iBridges'
        exit(0)
    
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Get results
    result = dict()
    info = get_ibridge_info()
    result = flatten_ibridge_info(info)
    
    # Write ibridge results to cache
    output_plist = os.path.join(cachedir, 'ibridge.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)

if __name__ == "__main__":
    main()
