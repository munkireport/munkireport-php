#!/usr/bin/python
# encoding: utf-8
"""
Bluetooth device info for munkireport.
Will return all Mice, Keyboards, and Trackpads connected along with
the power state of the bluetooth antenna.
"""

import subprocess
import os
import plistlib
import sys


def get_bluetooth_info():
    '''Uses system profiler to get bluetooth info for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPBluetoothDataType', '-xml']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    try:
        plist = plistlib.readPlistFromString(output)
        # system_profiler xml is an array
        sp_dict = plist[0]
        items = sp_dict['_items']
        sp_hardware_dict = items[0]
        return sp_hardware_dict
    except Exception:
        return {}


def bluetooth_power(info):
    '''Return the current bluetooth power status. True for on, False for off'''
    if info.get('local_device_title').get('general_power') == 'attrib_On':
        # return 'On'
        return True
    else:
        # return 'Off'
        return False


def bluetooth_devices(info):
    '''Creates a dictonary object from System Profiler output with useful
    data regarding bluetooth devices.'''
    devices = dict()
    for device in info.get('device_title', []):
        current = device.itervalues().next()
        if device.itervalues().next().get(
                'device_minorClassOfDevice_string') in [
                'Trackpad', 'Keyboard', 'Mouse'] and current.get(
                'device_isconnected') == 'attrib_Yes':
            apple_name = current.get('device_services')
            devices[apple_name] = current.get(
                    'device_batteryPercent', '-1').strip('%')
    return devices


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
    info = get_bluetooth_info()
    if info != {} :
        result.update(bluetooth_devices(info))
        result['bluetooth_power'] = bluetooth_power(info)
    else:
        result['bluetooth_power'] = '-1'
    
    # Write bluetooth results to cache
    output_plist = os.path.join(cachedir, 'bluetoothinfo.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
