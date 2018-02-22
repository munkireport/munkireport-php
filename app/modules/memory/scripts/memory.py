#!/usr/bin/python

import subprocess
import os
import plistlib
import sys
import string
import re

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
        
def get_memory_data():
    cmd = ['/usr/bin/vm_stat']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    meminfo = get_memory_pressure()
    
    for item in output.split("\n"):
        if "Pages free:" in item:
            meminfo['free'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages active:" in item:
            meminfo['active'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages inactive:" in item:
            meminfo['inactive'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages speculative:" in item:
            meminfo['speculative'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages throttled:" in item:
            meminfo['throttled'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages wired down:" in item:
            meminfo['wireddown'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages purgeable:" in item:
            meminfo['purgeable'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Translation faults" in item:
            meminfo['translationfaults'] = int(re.sub('[^0-9]','', (item)).strip())
        elif "Pages copy-on-write:" in item:
            meminfo['copyonwrite'] = int(re.sub('[^0-9]','', (item)).strip())
        elif "Pages zero filled:" in item:
            meminfo['zerofilled'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages reactivated:" in item:
            meminfo['reactivated'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages purged:" in item:
            meminfo['purged'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "File-backed pages:" in item:
            meminfo['filebacked'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Anonymous pages:" in item:
            meminfo['anonymous'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages stored in compressor:" in item:
            meminfo['storedincompressor'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pages occupied by compressor:" in item:
            meminfo['occupiedbycompressor'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Decompressions:" in item:
            meminfo['decompressions'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Compressions:" in item:
            meminfo['compressions'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Pageins:" in item:
            meminfo['pageins'] = int(re.sub('[^0-9]','', (item)).strip())
        elif "Pageouts:" in item:
            meminfo['pageouts'] = int(re.sub('[^0-9]','', (item)).strip())
        elif "Swapins:" in item:
            meminfo['swapins'] = int(re.sub('[^0-9]','', (item)).strip())*4096
        elif "Swapouts:" in item:
            meminfo['swapouts'] = int(re.sub('[^0-9]','', (item)).strip())*4096
            
    return meminfo

def get_memory_pressure():
    
    pressure = get_swap_data()

    if os.path.isfile('/usr/bin/memory_pressure'):

        cmd = ['/usr/bin/memory_pressure']
        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()

        for item in output.split("\n"):
            if "System-wide memory free percentage" in item:
                pressure['memorypressure'] = 100-int(re.sub('[^0-9]','', (item)).strip())

    return pressure
    
        
def get_swap_data():
    cmd = ['/usr/sbin/sysctl', 'vm.swapusage']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    swap = {'swapencrypted': 0}

    for item in output.split("  "):
        if "total = " in item:
            swap['swaptotal'] = int(re.sub('[^0-9]','', (item)).strip())*10000
        elif "used = " in item:
            swap['swapused'] = int(re.sub('[^0-9]','', (item)).strip())*10000
        elif "free = " in item:
            swap['swapfree'] = int(re.sub('[^0-9]','', (item)).strip())*10000
        elif "(encrypted)" in item:
            swap['swapencrypted'] = 1
    return swap
    
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
    i = 0
    for obj in array:
        if i == 0:
            device = get_memory_data()
            device['is_memory_upgradeable'] = is_memory_upgradeable
            device['global_ecc_state'] = global_ecc_state
            i = 1
        else:
            device = {}
        for item in obj:
            if item == '_items':
                out = out + flatten_memory_info(obj['_items'], is_memory_upgradeable, global_ecc_state)
            elif item == '_name' and obj[item] != "global_name":
                device['name'] = obj[item]
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

def remove_all(substr, str):
    index = 0
    length = len(substr)
    while string.find(str, substr) != -1:
        index = string.find(str, substr)
        str = str[0:index] + str[index+length:]
    return str
    
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
    
    del result[-1]
    
    # Write memory results to cache
    output_plist = os.path.join(cachedir, 'memoryinfo.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)


if __name__ == "__main__":
    main()
