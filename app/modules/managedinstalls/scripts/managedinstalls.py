#!/usr/bin/python
"""
Filter the result of /Library/Managed Installs/ManagedInstallReport.plist
to only the parts that represent the installed items
"""

import plistlib
import sys
import os

debug = False

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)
    if sys.argv[1] == 'debug':
        print '**** DEBUGGING ENABLED ****'
        debug = True
        import pprint
        pp = pprint.PrettyPrinter(indent=4)


def _DictFromPlist(path):
    """Returns a dict based on plist found in path"""
    try:
        return plistlib.readPlist(path)
    except Exception, message:
        raise Exception("Error creating plist from output: %s" % message)

def addItems(itemList, installList, status):
    for item in itemList:
        installList[item["name"]] = filterItem(item)
        installList[item["name"]]['status'] = status

def removeResult(itemList, installList):
    for item in itemList:
        #installList[item["name"]]['time'] = item.time
        if item.status == 0:
            installList[item["name"]]['installed'] = False
            installList[item["name"]]['status'] = 'uninstalled'
        else:
            installList[item["name"]]['status'] = 'uninstall_failed'

def installResult(itemList, installList):
    for item in itemList:
        #installList[item["name"]]['time'] = item.time
        if item.status == 0:
            installList[item["name"]]['installed'] = True
            installList[item["name"]]['status'] = 'installed'
        else:
            installList[item["name"]]['status'] = 'install_failed'


def filterItem(item):
    
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

    # Check if ManagedInstallReport exists
    ManagedInstallReport = '/Library/Managed Installs/ManagedInstallReport.plist'
    if not os.path.exists(ManagedInstallReport):
        print '%s is missing.' % ManagedInstallReport
        installReport = {}
    else:
        installReport = _DictFromPlist(ManagedInstallReport)

    installList = {}
    if installReport.get('ManagedInstalls'):
        addItems(installReport.ManagedInstalls, installList, 'installed')
    if installReport.get('ProblemInstalls'):
        addItems(installReport.ProblemInstalls, installList, 'install_failed')
    if installReport.get('ItemsToRemove'):
        addItems(installReport.ItemsToRemove, installList, 'pending_removal')
    if installReport.get('ItemsToInstall'):
        addItems(installReport.ItemsToInstall, installList, 'pending_install')
    if installReport.get('AppleUpdates'):
        addItems(installReport.AppleUpdates, installList, 'pending_install')
        
    # Update installList with results
    if installReport.get('RemovalResults'):
        removeResult(installReport.RemovalResults, installList)
    if installReport.get('InstallResults'):
        installResult(installReport.InstallResults, installList)

    if debug:
        pp.pprint(installList)

    # Write report to cache
    plistlib.writePlist(installList, "%s/managedinstalls.plist" % cachedir)

if __name__ == "__main__":
    main()


