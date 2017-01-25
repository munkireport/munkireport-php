#!/usr/bin/env python

# This is the clientside module for mbbr_status

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

    print 'Writing to plist...'
    print mbbrdata
    plistlib.writePlist(mbbrdata, '/Library/Preferences/com.malwarebytes.plist')
    print 'Done.'
except:
    mbbrdata = {'Entitlement status': 'unenrolled'}
