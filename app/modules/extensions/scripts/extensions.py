#! /usr/bin/python
# Written by Tuxudo
# With much help from frogor

import objc
from Foundation import NSBundle
from Foundation import NSURL

import plistlib
import os
import subprocess
import sys


# Create cache dir if it does not exist
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)
    
# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)
    
# Get kexts info
IOKit = NSBundle.bundleWithIdentifier_('com.apple.framework.IOKit')
functions = [('KextManagerCopyLoadedKextInfo', '@@@'),('KextManagerCreateURLForBundleIdentifier', '@@@'),]
objc.loadBundleFunctions(IOKit, globals(), functions)
kernel_dict = KextManagerCopyLoadedKextInfo(None, None)

info = {}    
count = 0

for kernelname in kernel_dict:
    if kernelname != '__kernel__' and not kernelname.startswith('com.apple.'):
        bundle_path = kernel_dict[kernelname]['OSBundlePath']
        bundle_version = kernel_dict[kernelname]['CFBundleVersion']
        bundle_executable = kernel_dict[kernelname]['OSBundleExecutablePath']
        bundle_codesign = ''

        from subprocess import Popen, PIPE
        stdout = Popen("/usr/bin/codesign -dv --verbose=4 '"+bundle_path+"'", shell=True, stderr=PIPE).stderr        
        output = stdout.read()
                
        for line in output.splitlines():
            if "Authority=Developer ID Application: " in line:
                bundle_codesign = line.replace("Authority=Developer ID Application: ", "")

        info[str(count)] = {'bundle_id':kernelname,'path':bundle_path,'version':bundle_version,'executable':bundle_executable,'codesign':bundle_codesign}
        count = count+1

# Write results to cache file
output_plist = os.path.join(cachedir, 'extensions.plist')
plistlib.writePlist(info, output_plist)