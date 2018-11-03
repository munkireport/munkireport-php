#!/usr/bin/python
# Applications for munkireport
# By Tuxudo

import subprocess
import os
import plistlib
import sys


def get_applications_info():
    '''Uses system profiler to get applications for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPApplicationsDataType', '-xml']
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

def flatten_applications_info(array):
    '''Un-nest applications, return array with objects with relevant keys'''
    out = []
    for obj in array:
        device = {'has64bit': 0}
        for item in obj:
            if item == '_items':
                out = out + flatten_applications_info(obj['_items'])
            elif item == '_name':
                device['name'] = obj[item]
            elif item == 'lastModified':
                device['last_modified'] = obj[item]
            elif item == 'obtained_from':
                device['obtained_from'] = obj[item]
            elif item == 'path':
                device['path'] = obj[item]
            elif item == 'runtime_environment':
                device['runtime_environment'] = obj[item]
            elif item == 'version':
                device['version'] = obj[item]
            elif item == 'info':
                device['info'] = obj[item]
            elif item == 'signed_by':
                device['signed_by'] = obj[item][0]
            elif item == 'has64BitIntelCode' and obj[item] == 'yes':
                device['has64bit'] = 1
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
    info = get_applications_info()
    result = flatten_applications_info(info)
    
    # Write applications results to cache
    output_plist = os.path.join(cachedir, 'applications.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)


if __name__ == "__main__":
    main()
