#!/usr/bin/python

"""
MDM status reporting tool. Requires macOS 10.13.4+ for command output compatibility.
"""

import subprocess
import os
import plistlib
import sys
import platform

def get_mdm_status():
    '''Uses profiles command to get MDM status for this machine.
    Requires macOS 10.13.4 due to MDM status output changes.'''
    cmd = ['/usr/bin/profiles', 'status', '-type', 'enrollment']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    
    values = output.split('\n')
    for value in values:
        if "Enrolled via DEP:" in value:
            enrollment_yes_no = value.split(':')[1].lstrip()
            if "Yes" in enrollment_yes_no:
                enrollment_type = "DEP"
            else:
                enrollment_type = "Non-DEP"
                
        if "MDM enrollment:" in value:
            mdm_enrollment = value.split(':')[1].lstrip()
            
    result = {}
    result.update({'mdm_enrollment_type': enrollment_type})
    result.update({'mdm_enrolled': mdm_enrollment})
    return result

def getMinorOsVersion():
    """Returns the minor OS version."""
    os_version_tuple = platform.mac_ver()[0].split('.')
    return int(os_version_tuple[1])

def getPatchOsVersion():
    """Returns the minor OS version."""
    os_version_tuple = platform.mac_ver()[0].split('.')
    return int(os_version_tuple[2])

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
    
    if getMinorOsVersion() <= 13 and getPatchOsVersion() < 4:
        #result = {'mdm_enrolled': 'Output Not Supported', 'enrollment_type': 'Output Not Supported'}
        exit(0) # OS not supported, don't report anything
    else:
        # Get results
        result = dict()
        result = get_mdm_status()
    
    # Write mdm status results to cache
    output_plist = os.path.join(cachedir, 'mdm_status.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
