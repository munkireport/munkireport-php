#!/usr/bin/python

"""
Extracts information about the external displays from System Profiler
"""

import sys
import os
import subprocess
import plistlib

sys.path.insert(0, '/usr/local/munki')

from munkilib import FoundationPlist

def get_displays_info():
    '''Uses system profiler to get display info for this machine.'''
    cmd = ['/usr/sbin/system_profiler', 'SPDisplaysDataType', '-xml']
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

def flatten_displays_info(array, localization):
    '''Un-nest displays, return array with objects with relevant keys'''
    out = []
    for obj in array:
        display = {}
        for item in obj:
            if item == '_items':
                out = out + flatten_displays_info(obj['_items'], localization)
            elif item == 'spdisplays_ndrvs':
                out = out + flatten_displays_info(obj['spdisplays_ndrvs'], localization)
            elif item == '_spdisplays_displayport_device':
                out = out + flatten_displays_info(obj['_spdisplays_displayport_device'], localization)

            elif item == 'spdisplays_display-serial-number':
                display['display_serial'] = obj[item]
            elif item == '_spdisplays_display-vendor-id':
                # 756e6b6e is used to show display is virtual display
                if obj[item] == "756e6b6e":
                    display['virtual_device'] = 1    
                    display['vendor'] = "Virtual Display"
                else:
                    try:
                        display['virtual_device'] = to_bool(obj['_spdisplays_virtualdevice'])
                        display['vendor'] = obj[item].strip()
                    except KeyError, error:
                        display['vendor'] = obj[item].strip()
                
                # Get Internal/External
                try:
                    display['type'] = type_get(obj['spdisplays_builtin'])
                except KeyError, error:
                    try:
                        if obj.get('spdisplays_display-serial-number', None):
                            display['type'] = 1 #External
                        elif obj['_spdisplays_display-vendor-id'] == "610":
                            display['type'] = 0 #Internal
                        else:
                            display['type'] = 1 #External
                    except KeyError, error: # This catches the error for 10.6 where there is no vendor for built-in displays
                        display['type'] = 0 #Internal
                    
            elif item == '_name' and obj[item] is not "spdisplays_displayport_info" and obj[item] is not "spdisplays_display_connector" and not obj[item].startswith('kHW_') and not obj[item].startswith('NVIDIA')  and not obj[item].startswith('AMD') and not obj[item].startswith('ATI') and not obj[item].startswith('Intel'):
                if obj[item] == "spdisplays_display":
                    display['model'] = "Virtual Display"
                else:
                    display['model'] = obj[item].strip()
                    
                try:
                    display['display_asleep'] = to_bool(obj['spdisplays_asleep'])
                except KeyError, error:
                    display['display_asleep'] = 0
                    
                # Set inital Retina
                display['retina'] = 0
                
            elif item == '_spdisplays_pixels':
                display['native'] = obj[item].strip()
                
            elif item == 'spdisplays_pixelresolution':
                # Set Retina 
                if "etina" in obj[item]:
                    display['retina'] = 1
                    
            elif item == 'spdisplays_resolution':
                try:
                    try:
                        display['current_resolution'] = localization[obj[item]].strip()
                    except KeyError, error:
                        display['current_resolution'] = obj[item].strip()
                except KeyError, error:
                    display['current_resolution'] = obj[item].strip()
                    
            elif item == '_spdisplays_resolution':
                try:
                    try:
                        display['ui_resolution'] = localization[obj[item]].strip()
                    except KeyError, error:
                        display['ui_resolution'] = obj[item].strip()                  
                except KeyError, error:
                    display['ui_resolution'] = obj[item].strip()

            elif item == 'spdisplays_depth':
                try:
                    display['color_depth'] = localization[obj[item]].strip()
                except KeyError, error:
                    display['color_depth'] = obj[item].strip()
            elif item == 'spdisplays_display_type':
                try:
                    display['display_type'] = localization[obj[item]].strip()
                except KeyError, error:
                    display['display_type'] = obj[item].strip()
                # Set Retina 
                if "etina" in obj[item]:
                    display['retina'] = 1
            elif item == 'spdisplays_main':
                display['main_display'] = to_bool(obj[item])
            elif item == 'spdisplays_mirror':
                display['mirror'] = to_bool(obj[item])
            elif item == 'spdisplays_mirror_status':
                try:
                    display['mirror_status'] = localization[obj[item]].strip()
                except KeyError, error:
                    display['mirror_status'] = obj[item].strip()               
            elif item == 'spdisplays_online':
                display['online'] = to_bool(obj[item])
            elif item == 'spdisplays_interlaced':
                display['interlaced'] = to_bool(obj[item])
            elif item == 'spdisplays_rotation':
                display['rotation_supported'] = to_bool(obj[item])
            elif item == 'spdisplays_television':
                display['television'] = to_bool(obj[item])
            elif item == 'spdisplays_ambient_brightness':
                display['ambient_brightness'] = to_bool(obj[item])
            elif item == 'spdisplays_automatic_graphics_switching':
                display['automatic_graphics_switching'] = to_bool(obj[item])
            elif item == '_spdisplays_EDR_Enabled':
                display['edr_enabled'] = to_bool(obj[item])
            elif item == '_spdisplays_EDR_Limit':
                display['edr_limit'] = float(obj[item])
            elif item == '_spdisplays_EDR_Supported':
                display['edr_supported'] = to_bool(obj[item])
            elif item == 'spdisplays_connection_type':
                try:
                    display['connection_type'] = localization[obj[item]].strip()
                except KeyError, error:
                    display['connection_type'] = obj[item].strip()                    
            elif item == 'spdisplays_displayport_DPCD_version':
                display['dp_dpcd_version'] = obj[item].strip()
            elif item == 'spdisplays_displayport_current_bandwidth':
                display['dp_current_bandwidth'] = obj[item].strip()
            elif item == 'spdisplays_displayport_current_lanes':
                display['dp_current_lanes'] = int(obj[item])
            elif item == 'spdisplays_displayport_current_spread':
                display['dp_current_spread'] = obj[item].strip()
            elif item == 'spdisplays_displayport_hdcp_capability':
                display['dp_hdcp_capability'] = to_bool(obj[item])
            elif item == 'spdisplays_displayport_max_bandwidth':
                display['dp_max_bandwidth'] = obj[item].strip()
            elif item == 'spdisplays_displayport_max_lanes':
                display['dp_max_lanes'] = int(obj[item])
            elif item == 'spdisplays_displayport_max_spread':
                display['dp_max_spread'] = obj[item].strip()
            elif item == 'spdisplays_dynamic_range':
                try:
                    display['dynamic_range'] = localization[obj[item]].strip()
                except KeyError, error:
                    display['dynamic_range'] = obj[item] .strip()
            elif item == 'spdisplays_displayport_adapter_firmware_version':
                display['dp_adapter_firmware_version'] = obj[item].strip()
                
            elif item == '_spdisplays_display-week':
                # Manufactured section
                # from https://en.wikipedia.org/wiki/Extended_Display_Identification_Data#EDID_1.4_data_format
                # If week is 0 or 255, year is the model year.
                if int(obj['_spdisplays_display-week']) == 255 or int(obj['_spdisplays_display-week']) == 0:
                    display['manufactured'] = str(obj['_spdisplays_display-year']) + ' Model'
                else:
                    display['manufactured'] = obj['_spdisplays_display-year'] + '-' + obj['_spdisplays_display-week']

        if display:
            out.append(display)
            
    return out
    

def to_bool(s):
    if s == True or s == "spdisplays_yes" or s == "spdisplays_on" or s == 'spdisplays_supported' or s == 'spdisplays_supported' or s == 'spdisplays_displayport_hdcp_capable':
        return 1
    else:
        return 0
    
def type_get(s):
    if s == "spdisplays_yes":
        return 0
    else:
        return 1
    
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
    info = get_displays_info()
    
    # Read in English localizations from SystemProfiler
    localization = FoundationPlist.readPlist('/System/Library/SystemProfiler/SPDisplaysReporter.spreporter/Contents/Resources/English.lproj/Localizable.strings')

    result = flatten_displays_info(info, localization)
    
    # Write displays results to cache
    output_plist = os.path.join(cachedir, 'displays.plist')
    plistlib.writePlist(result, output_plist)
    #print plistlib.writePlistToString(result)
    

if __name__ == "__main__":
    main()
