#!/usr/bin/python

"""
USB device info for munkireport.
Will return all Mice, Keyboards, and Trackpads connected along with
manufacturer, name, if internal.
"""

import subprocess
import os
import plistlib
import sys


def get_usb_info():
    '''Uses system profiler to get usb info for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPUSBDataType', '-xml']
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

def flatten_usb_info(array):
    '''Un-nest USB devices, return array with objects with relevant keys'''
    out = []
    for obj in array:
        device = {'name': '', 'internal': 0, 'media': 0}
        for item in obj:
            if item == '_items':
                out = out + flatten_usb_info(obj['_items'])
            elif item == '_name':
                device['name'] = obj[item]
            elif item == 'vendor_id':
                device['vendor_id'] = obj[item]
            elif item == 'manufacturer':
                device['manufacturer'] = obj[item]
            elif item == 'device_speed':
                device['device_speed'] = obj[item]
            elif item == 'bus_power':
                device['bus_power'] = obj[item]
            elif item == 'bus_power_used':
                device['bus_power_used'] = obj[item]
            elif item == 'extra_current_used':
                device['extra_current_used'] = obj[item]
            elif item == 'Built-in_Device' and obj[item] == 'Yes':
                device['internal'] = 1
            elif item == 'Media' or 'removable_media' :
                device['media'] = 1
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
    info = get_usb_info()
    result = flatten_usb_info(info)
    
        # Write bluetooth results to cache
    output_plist = os.path.join(cachedir, 'usbinfo.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)


if __name__ == "__main__":
    main()
