#!/usr/bin/python
"""
Filter the result of /Library/Managed Installs/ManagedInstallReport.plist
to only the parts that represent the installed items
"""

import subprocess
import plistlib
import sys
import os

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)


def _DictFromPlist(path):
    """Returns a dict based on plist found in path"""
    try:
        return plistlib.readPlist(path)
    except Exception, message:
        raise Exception("Error creating plist from output: %s" % message)

def addItems(itemList, globalList, status):
    for item in itemList:
        globalList[item["name"]] = filterItem(item)
        globalList[item["name"]]['status'] = status

def removeResult(itemList, globalList):
    for item in itemList:
        #globalList[item["name"]]['time'] = item.time
        if item.status == 0:
            globalList[item["name"]]['installed'] = False
            globalList[item["name"]]['status'] = 'uninstalled'
        else:
            globalList[item["name"]]['status'] = 'uninstall_failed'

def installResult(itemList, globalList):
    for item in itemList:
        #globalList[item["name"]]['time'] = item.time
        if item.status == 0:
            globalList[item["name"]]['installed'] = True
            globalList[item["name"]]['status'] = 'installed'
        else:
            globalList[item["name"]]['status'] = 'install_failed'


def filterItem(item):
    """ Only return keys for item """
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


# Create cache dir if it does not exist
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

installReport = _DictFromPlist('/Library/Managed Installs/ManagedInstallReport.plist')

# InstallResults
# ItemsToInstall
# ItemsToRemove
# ManagedInstalls
# ProblemInstalls
# RemovalResults
# RemovedItems

globalList = {}
addItems(installReport.ManagedInstalls, globalList, 'installed')
addItems(installReport.ProblemInstalls, globalList, 'install_failed')
addItems(installReport.ItemsToRemove, globalList, 'pending_removal')
addItems(installReport.ItemsToInstall, globalList, 'pending_install')

removeResult(installReport.RemovalResults, globalList)
installResult(installReport.InstallResults, globalList)

# Write report to cache
plistlib.writePlist(globalList, "%s/managedinstalls.plist" % cachedir)



