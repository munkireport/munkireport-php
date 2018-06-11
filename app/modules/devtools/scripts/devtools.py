#!/usr/bin/python

"""
Devtools for munkireport.
Will return all details about Xcode and other development tools
"""

import subprocess
import os
import plistlib
import sys
import platform
import re

def getOsVersion():
    """Returns the minor OS version."""
    os_version_tuple = platform.mac_ver()[0].split('.')
    return int(os_version_tuple[1])

def get_dev_info():
    '''Uses system profiler to get dev tools for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPDeveloperToolsDataType', '-xml']
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
    
def get_additional_tools():
    
    # Create the variables
    xquartz = ''
    cli_tools = ''
        
    # Get XQuartz version, only for 10.6 and higher
    if getOsVersion() > 5:
        cmd = ['/usr/sbin/pkgutil', '--pkg-info=org.macosforge.xquartz.pkg']
        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()
        for line in output.splitlines():
            if "version: " in line:
                xquartz = line.split(' ')[1]
                break
            else:
                xquartz = ''
    
    # Get command lines tools version, different for 10.8 and below
    if getOsVersion() < 9:
        cmd = ['/usr/sbin/pkgutil', '--pkg-info=com.apple.pkg.DeveloperToolsCLI']
    else:
        cmd = ['/usr/sbin/pkgutil', '--pkg-info=com.apple.pkg.CLTools_Executables']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    for line in output.splitlines():
        if "version: " in line:
            cli_tools = line.split(' ')[1]
            break
        else:
            cli_tools = ''
    
    # Build list containing additional tools information
    items = {'cli_tools': cli_tools, 'xquartz': xquartz}
    return items

def flatten_dev_info(array):
    '''Un-nest dev info, return array with objects with relevant keys'''
    out = []
    for obj in array:
        device = get_additional_tools()
        for item in obj:
            if item == '_items':
                out = out + flatten_dev_info(obj['_items'])
            elif item == 'spdevtools_path':
                device['devtools_path'] = obj[item]
            elif item == 'spdevtools_version':
                device['devtools_version'] = obj[item]
                
            # Process each devtool app
            elif item == 'spdevtools_apps':
                for tool in obj['spdevtools_apps']:
                    if tool == 'spinstruments_app':
                        device['instruments_version'] = obj['spdevtools_apps'][tool]
                    elif tool == 'spxcode_app':
                        device['xcode_version'] = obj['spdevtools_apps'][tool]
                    elif tool == 'spdashcode_app':
                        device['dashcode_version'] = obj['spdevtools_apps'][tool]
                    elif tool == 'spib_app':
                        device['interface_builder_version'] = obj['spdevtools_apps'][tool]
                            
            # Process each SDK
            elif item == 'spdevtools_sdks':
                for sdk in obj['spdevtools_sdks']:
                    if sdk == 'iOS' or sdk == 'spios_sdks' or sdk == 'spiphoneos_sdks':                        
                        device['ios_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
                    elif sdk == 'iOS Simulator' or sdk == 'spiossim_sdks' or sdk == 'spiphonesim_sdks':
                        device['ios_simulator_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
                    elif sdk == 'macOS' or sdk == 'sposx_sdks' or sdk == 'spmacosx_sdks':
                        device['macos_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
                    elif sdk == 'tvOS':
                        device['tvos_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
                    elif sdk == 'tvOS Simulator':
                        device['tvos_simulator_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
                    elif sdk == 'watchOS':
                        device['watchos_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
                    elif sdk == 'watchOS Simulator':
                        device['watchos_simulator_sdks'] = ''.join('{} {}, '.format(key, val) for key, val in obj['spdevtools_sdks'][sdk].items())
           
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
    info = get_dev_info()
    result = flatten_dev_info(info)
    
    # Write devtools results to cache
    output_plist = os.path.join(cachedir, 'devtools.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)

if __name__ == "__main__":
    main()
