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

# Don't skip manual check
if len(sys.argv) > 1:
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
        
    # Collect Errors, Warnings (as JSON?)
    # EndTime, StartTime, ManifestName, (Conditions->catalogs?)
    # ManagedInstallVersion
    # Some statistics

    # pylint: disable=E1103
    report_list = {}
    items = ['EndTime', 'StartTime', 'ManifestName', 'ManagedInstallVersion', \
        'Errors', 'Warnings', 'RunType']
    for item in items:
        if install_report.get(item):
            report_list[item] = install_report[item];
    # pylint: enable=E1103

    if DEBUG:
        PP.pprint(report_list)

    # Write report to cache
    plistlib.writePlist(report_list, "%s/munkireport.plist" % cachedir)

if __name__ == "__main__":
    main()


