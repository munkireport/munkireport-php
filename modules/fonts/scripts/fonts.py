#!/usr/bin/python

"""
Fonts information generator for munkireport.
"""

import subprocess
import os
import plistlib
import sys


def get_fonts():
    '''Uses system profiler to get fonts for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPFontsDataType', '-xml']
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

def flatten_get_fonts(array):
    '''Un-nest fonts, return array with objects with relevant keys'''
    out = []
    for obj in array:
        device = {'name': '', 'enabled': 0, 'type_enabled': 0, 'copy_protected': 0, 'duplicate': 0, 'embeddable': 0, 'outline': 0, 'valid': 0}
        for item in obj:
            if item == '_items':
                out = out + flatten_get_fonts(obj['_items'])
            elif item == '_name':
                device['name'] = obj[item]
            elif item == 'path':
                device['path'] = obj[item]
            elif item == 'type':
                device['type'] = obj[item]
            elif item == 'enabled' and obj[item] == 'Yes':
                device['enabled'] = 1
                    
            # Process each typeface within font
            elif item == 'typefaces':
                for font in obj['typefaces']:
                    for fontitem in font:
                        if fontitem == '_name':
                            device['type_name'] = font[fontitem]
                        elif fontitem == 'family':
                            device['family'] = font[fontitem]
                        elif fontitem == 'fullname':
                            device['fullname'] = font[fontitem]
                        elif fontitem == 'style':
                            device['style'] = font[fontitem]
                        elif fontitem == 'unique':
                            device['unique_id'] = font[fontitem]
                        elif fontitem == 'version':
                            device['version'] = font[fontitem]
                        elif fontitem == 'vendor':
                            device['vendor'] = font[fontitem]
                        elif fontitem == 'trademark':
                            device['trademark'] = font[fontitem]
                        elif fontitem == 'copyright':
                            device['copyright'] = font[fontitem]
                        elif fontitem == 'description':
                            device['description'] = font[fontitem]
                        elif fontitem == 'designer':
                            device['designer'] = font[fontitem]
                        elif fontitem == 'copy_protected' and font[fontitem] == 'yes':
                            device['copy_protected'] = 1
                        elif fontitem == 'duplicate' and font[fontitem] == 'yes':
                            device['duplicate'] = 1
                        elif fontitem == 'embeddable' and font[fontitem] == 'yes':
                            device['embeddable'] = 1
                        elif fontitem == 'enabled' and font[fontitem] == 'yes':
                            device['type_enabled'] = 1
                        elif fontitem == 'outline' and font[fontitem] == 'yes':
                            device['outline'] = 1
                        elif fontitem == 'valid' and font[fontitem] == 'yes':
                            device['valid'] = 1

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
    info = get_fonts()
    result = flatten_get_fonts(info)
    
    # Write font results to cache
    output_plist = os.path.join(cachedir, 'fonts.plist')
    plistlib.writePlist(result, output_plist)


if __name__ == "__main__":
    main()
