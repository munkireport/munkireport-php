#!/usr/bin/python
"""
Filter the result of /Library/Managed Installs/MANAGED_INSTALL_REPORT.plist
to only the parts that represent the installed items
"""

import plistlib
import sys
import os

DEBUG = False
MANAGED_INSTALL_REPORT = '/Library/Managed Installs/ManagedInstallReport.plist'

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)
    if sys.argv[1] == 'debug':
        print '**** DEBUGGING ENABLED ****'
        DEBUG = True
        import pprint
        PP = pprint.PrettyPrinter(indent=4)


def dict_from_plist(path):
    """Returns a dict based on plist found in path"""
    try:
        return plistlib.readPlist(path)
    except Exception, message:
        raise Exception("Error creating plist from output: %s" % message)

def add_items(item_list, install_list, status, item_type):
    """Add item to list and set status"""
    for item in item_list:
        # Check if applesus item
        if item.get('productKey'):
            name = item['productKey']
        else:
            name = item['name']

        install_list[name] = filter_item(item)
        install_list[name]['status'] = status
        install_list[name]['type'] = item_type
        
def add_removeditems(item_list, install_list, status):
    """Add removed item to list and set status"""
    for item in item_list:
        install_list[item] = {'name': item, 'status': status,
            'installed': False, 'display_name': item, 'type': 'munki'}

def remove_result(item_list, install_list):
    """Update list according to result"""
    for item in item_list:
        #install_list[item['name']]['time'] = item.time
        if item.status == 0:
            install_list[item['name']]['installed'] = False
            install_list[item['name']]['status'] = 'uninstalled'
        else:
            install_list[item['name']]['status'] = 'uninstall_failed'

def install_result(item_list, install_list):
    """Update list according to result"""
    for item in item_list:
        #install_list[item['name']]['time'] = item.time
        
        # Check if applesus item
        if item.get('productKey'):
            name = item['productKey']
        else:
            name = item['name']
            
        if item.status == 0:
            install_list[name]['installed'] = True
            install_list[name]['status'] = 'installed'
        else:
            install_list[name]['status'] = 'install_failed'


def filter_item(item):
    """Only return specified keys"""
    keys = ["display_name", "installed_version", "installed_size",
            "version_to_install", "installed", "note"]

    out = {}
    for key in keys:
        try:
            out[key] = item[key]
        # pylint: disable=pointless-except
        except KeyError:  # not all objects have all these attributes
            pass

    return out

def main():
    """Main"""
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Check if MANAGED_INSTALL_REPORT exists
    if not os.path.exists(MANAGED_INSTALL_REPORT):
        print '%s is missing.' % MANAGED_INSTALL_REPORT
        install_report = {}
    else:
        install_report = dict_from_plist(MANAGED_INSTALL_REPORT)

    # pylint: disable=E1103
    install_list = {}
    if install_report.get('ManagedInstalls'):
        add_items(install_report.ManagedInstalls, install_list, 'installed', 'munki')
    if install_report.get('AppleUpdates'):
        add_items(install_report.AppleUpdates, install_list, \
            'pending_install', 'applesus')
    if install_report.get('ProblemInstalls'):
        add_items(install_report.ProblemInstalls, install_list, \
            'install_failed', 'munki')
    if install_report.get('ItemsToRemove'):
        add_items(install_report.ItemsToRemove, install_list, \
            'pending_removal', 'munki')
    if install_report.get('RemovedItems'):
        add_removeditems(install_report.RemovedItems, install_list, 'removed')
    if install_report.get('ItemsToInstall'):
        add_items(install_report.ItemsToInstall, install_list, \
            'pending_install', 'munki')

    # Update install_list with results
    if install_report.get('RemovalResults'):
        remove_result(install_report.RemovalResults, install_list)
    if install_report.get('InstallResults'):
        install_result(install_report.InstallResults, install_list)
    # pylint: enable=E1103

    if DEBUG:
        PP.pprint(install_list)

    # Write report to cache
    plistlib.writePlist(install_list, "%s/managedinstalls.plist" % cachedir)

if __name__ == "__main__":
    main()


