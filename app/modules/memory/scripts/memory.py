#!/usr/bin/python

import subprocess
import os
import plistlib
import sys

def get_memory_info():
    '''Uses system profiler to get memory info for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPMemoryDataType', '-xml']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    try:
        plist = plistlib.readPlistFromString(output)
        # system_profiler xml is an array
        sp_dict = plist[0]
        items = sp_dict['_items']
        return (items)
    except Exception:
        return {}
    
def memory_upgradeable(array):

    if 'is_memory_upgradeable' in array[0]:
        if array[0]['is_memory_upgradeable'] == 'No' :
            return 0
        else:
            return 1
    else:
            return 1

def ecc_state(array):

    if array[0]['global_ecc_state'] == 'ecc_enabled' :
        return 1
    elif array[0]['global_ecc_state'] == 'ecc_errors' :
        return 2
    else:
        return 0

def flatten_memory_info(array, is_memory_upgradeable, global_ecc_state):
    '''Un-nest memory information, return array with objects with relevant keys'''
    out = []
    for obj in array:
        device = {}
        for item in obj:
            if item == '_items':
                out = out + flatten_memory_info(obj['_items'], is_memory_upgradeable, global_ecc_state)
            elif item == '_name' and obj[item] != "global_name":
                device['name'] = obj[item]
                device['is_memory_upgradeable'] = is_memory_upgradeable
                device['global_ecc_state'] = global_ecc_state
            elif item == 'dimm_manufacturer':
                device['dimm_manufacturer'] = obj[item]
            elif item == 'dimm_part_number':
                device['dimm_part_number'] = obj[item]
            elif item == 'dimm_serial_number' and obj[item] != "-":
                device['dimm_serial_number'] = obj[item]
            elif item == 'dimm_size':
                device['dimm_size'] = obj[item]
            elif item == 'dimm_speed':
                device['dimm_speed'] = obj[item]
            elif item == 'dimm_status':
                device['dimm_status'] = obj[item]
            elif item == 'dimm_type':
                device['dimm_type'] = obj[item]
            elif item == 'dimm_ecc_errors':
                device['dimm_ecc_errors'] = obj[item]

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
    info = get_memory_info()
    is_memory_upgradeable = memory_upgradeable(info)
    global_ecc_state = ecc_state(info)
    result = flatten_memory_info(info, is_memory_upgradeable, global_ecc_state)
    
    # Write memory results to cache
    output_plist = os.path.join(cachedir, 'memoryinfo.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)


if __name__ == "__main__":
    main()
