#!/usr/bin/env python

# This is the clientside module for mbbr_status

import os
import subprocess
import StringIO
import plistlib

try:
    rundata = subprocess.check_output("/usr/local/bin/mbbr register", shell=True)
    buf = StringIO.StringIO(rundata)
    keylist = buf.read().replace('\t', '').splitlines()

    mbbrdata = {}

    for i in keylist:
        if i:
            pair = i.split(':', 1)
            if len(pair) < 2:
                continue
            key, value = pair
            mbbrdata[key] = value
except:
    mbbrdata = {'Entitlement status': 'unenrolled'}

cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
output_plist = os.path.join(cachedir, 'malwarebytes.plist')

print 'Writing to plist...'
plistlib.writePlist(mbbrdata, output_plist)
print 'Done.'
