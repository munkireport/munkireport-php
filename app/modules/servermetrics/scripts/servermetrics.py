#!/usr/bin/env python

"""
Based on script by CCL Forensics
"""

import sys
import os
import os.path as path
sys.path.append('/usr/local/munki/munkireportlib')
import re
import ccl_asldb
import json
import platform

# Event Type strings to array position logformat 1
EVENTS = {  'filesharing.sessions.afp': 0,
            'filesharing.sessions.smb': 1,
            'caching.bytes.fromcache.toclients': 2,
            'caching.bytes.fromorigin.toclients': 3,
            'caching.bytes.frompeers.toclients': 4,
            'system.cpu.utilization.user': 5,
            'system.memory.physical.wired': 6,
            'system.memory.physical.active': 7,
            'system.cpu.utilization.idle': 8,
            'system.memory.physical.free': 9,
            'system.network.throughput.bytes.in': 10,
            'system.memory.pressure': 11,
            'system.cpu.utilization.system': 12,
            'system.network.throughput.bytes.out': 13,
            'system.cpu.utilization.nice': 14,
            'system.memory.physical.inactive': 15}

# Event Type strings to array position - logformat 0
FMT_PREV = {
    'NetBytesInPerSecond': 10,
    'NetBytesOutPerSecond': 13,
    'UserCPU': 5,
    'IdleCPU': 8,
    'SystemCPU': 12,
    'PhysicalMemoryInactive': 15,
    'PhysicalMemoryActive': 7,
    'PhysicalMemoryFree': 9,
    'PhysicalMemoryWired': 6
}

def getOsVersion():
    """Returns the minor OS version."""
    os_version_tuple = platform.mac_ver()[0].split('.')
    return int(os_version_tuple[1])

def __main__():
    
    debug = False
    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)
        if sys.argv[1] == 'debug':
            print '******* DEBUG MODE ********'
            debug = True

    # Determine logformat based on OS version
    logFormat = 1
    if getOsVersion() < 10:
        logFormat = 0

    if getOsVersion() >= 11:
        #If on 10.11 or higher, skip rest of this script
        return

    # Set path according to logformat
    if logFormat == 0:
        input_dir = '/var/log/performance/'
    else:
        input_dir = '/var/log/servermetricsd/'

    output_file_path = '/usr/local/munki/preflight.d/cache/servermetrics.json'

    out = {}

    if os.path.isdir(input_dir):
        for file_path in os.listdir(input_dir):
            file_path = path.join(input_dir, file_path)
            if debug:
                print("Reading: \"{0}\"".format(file_path))
            try:
                f = open(file_path, "rb")
            except IOError as e:
                if debug:
                    print("Couldn't open file {0} ({1}). Skipping this file".format(file_path, e))
                continue

            try:
                db = ccl_asldb.AslDb(f)
            except ccl_asldb.AslDbError as e:
                if debug:
                    print("Couldn't open file {0} ({1}). Skipping this file".format(file_path, e))
                f.close()
                continue

            timestamp = ''
            
            for record in db:
                if debug:
                    print "%s %s" % (record.timestamp, record.message.decode('UTF-8'))
                # print(record.key_value_dict);

                fmt_timestamp = record.timestamp.strftime('%Y-%m-%d %H:%M:%S')
                if fmt_timestamp != timestamp:
                    timestamp = fmt_timestamp
                    out[timestamp] = [0]*16

                if logFormat == 0:
                    for key in record.key_value_dict:
                        #print "%s %s" % (key, record.key_value_dict[key])
                        # Look up key in index
                        index = FMT_PREV.get(key, -1)
                        if index >= 0:
                            try:
                                val = float(record.key_value_dict[key])
                                if 'CPU' in key:
                                    # correct cpu usage (has to be a fraction)
                                    val = val / 100
                                elif 'Memory' in key:
                                    # correct memory usage
                                    val = val * 4096
                                out[timestamp][index] = val
                            except ValueError as e:
                                continue
                elif logFormat == 1:
                    key_val = [x.strip() for x in record.message.split(':')]
                    index = EVENTS.get(key_val[0], -1)
                    if index >= 0:
                        try:
                            out[timestamp][index] = float(key_val[1])
                        except ValueError as e:
                            continue

            f.close()
    else:
        if debug:
            print "Log directory %s not found" % input_dir

    # Open and write output
    output = open(output_file_path, "w")
    output.write(json.dumps(out))
    output.close()

if __name__ == "__main__":
    __main__()