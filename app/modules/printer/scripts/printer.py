#!/usr/bin/python

# Original instance https://github.com/glarizza/scripts/blob/5c49cef74f0194411273bb425da1cf065b9e0978/python/Command.py

# Printer Location is not recorded in system profiler

import subprocess
import plistlib
import sys
import os

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)

# Create cache dir if it does not exist
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

command = ['system_profiler', '-xml', 'SPPrintersDataType']
task = subprocess.Popen(command,
                        stdout=subprocess.PIPE,
                        stderr=subprocess.PIPE)

(stdout, stderr) = task.communicate()
printers = plistlib.readPlistFromString(stdout)
printers = printers[0]['_items']

result = ''

# If no printers are installed then write 0 and exit
if not task:
    result = ''
    # Write to disk
    txtfile = open("%s/printer.txt" % cachedir, "w")
    txtfile.write(result)
    txtfile.close()
    exit(0)

#loop through all printers
for printer in printers:
    if printer.get('uri'):
        result += 'Name: ' + printer['_name'] + '\n'
        result += 'PPD: ' + printer['ppd'] + '\n'
        result += 'Driver Version: ' + printer['ppdfileversion'] + '\n'
        result += 'URL: ' + printer['uri'] + '\n'
        result += 'Default Set: ' + printer['default'] + '\n'
        result += 'Printer Status: ' + printer['status'] + '\n'
        try:
            result += 'Printer Sharing: ' + printer['shared']
        except KeyError:
            result += 'Printer Sharing: no'
        result += '\n----------\n'


# Write to disk
txtfile = open("%s/printer.txt" % cachedir, "w")
txtfile.write(result.encode('utf-8'))
txtfile.close()
