#!/usr/bin/python
"""
munkiinfo for munkireport
"""

import os
import plistlib
import sys

sys.path.append('/usr/local/munki')
try:
    from munkilib import FoundationPlist
    from munkilib import munkicommon
except ImportError, msg:
    print "%s" % msg
    exit(1)

def pref_to_str(pref_value):
    """If the value of a preference is None return a string type
    so we can write this data to a plist."""
    if pref_value is None:
        # convert to strings
        pref_value = str(pref_value)
        return pref_value
    else:
        return pref_value

# Munki common preferences
# Pylint doesn't like munki variables
# pylint: disable=C0103
ManagedInstallDir = pref_to_str(munkicommon.pref('ManagedInstallDir'))
SoftwareRepoURL = pref_to_str(munkicommon.pref('SoftwareRepoURL'))
PackageURL = pref_to_str(munkicommon.pref('PackageURL'))
CatalogURL = pref_to_str(munkicommon.pref('CatalogURL'))
ManifestURL = pref_to_str(munkicommon.pref('ManifestURL'))
IconURL = pref_to_str(munkicommon.pref('IconURL'))
ClientResourceURL = pref_to_str(munkicommon.pref('ClientResourceURL'))
HelpURL = pref_to_str(munkicommon.pref('HelpURL'))
InstallAppleSoftwareUpdates = pref_to_str(munkicommon.pref('InstallAppleSoftwareUpdates'))
AppleSoftwareUpdatesOnly = pref_to_str(munkicommon.pref('AppleSoftwareUpdatesOnly'))
SoftwareUpdateServerURL = pref_to_str(munkicommon.pref('AppleSoftwareUpdatesOnly'))
DaysBetweenNotifications = pref_to_str(munkicommon.pref('DaysBetweenNotifications'))
UseClientCertificate = pref_to_str(munkicommon.pref('UseClientCertificate'))
SuppressUserNotification = pref_to_str(munkicommon.pref('SuppressUserNotification'))
SuppressAutoInstall = pref_to_str(munkicommon.pref('SuppressAutoInstall'))
SuppressStopButtonOnInstall = pref_to_str(munkicommon.pref('SuppressStopButtonOnInstall'))
FollowHTTPRedirects = pref_to_str(munkicommon.pref('FollowHTTPRedirects'))
UnattendedAppleUpdates = pref_to_str(munkicommon.pref('UnattendedAppleUpdates'))
InstallRequiresLogout = pref_to_str(munkicommon.pref('InstallRequiresLogout'))
LocalOnlyManifest = pref_to_str(munkicommon.pref('LocalOnlyManifest'))

def get_munkiprotocol():
    """The protocol munki is using"""
    try:
        return SoftwareRepoURL.split(":")[0]
    except AttributeError:
        return 'Could not obtain protocol'

def munkiinfo_report():
    """Build our report data for our munkiinfo plist"""
    munkiprotocol = get_munkiprotocol()

    if 'file' in munkiprotocol:
        munkiprotocol = 'localrepo'

    report = ([{
        'ManagedInstallDir': ManagedInstallDir,
        'SoftwareRepoURL': SoftwareRepoURL,
        'PackageURL': PackageURL,
        'CatalogURL': CatalogURL,
        'ManifestURL': ManifestURL,
        'IconURL': IconURL,
        'ClientResourceURL': ClientResourceURL,
        'HelpURL': HelpURL,
        'InstallAppleSoftwareUpdates': InstallAppleSoftwareUpdates,
        'AppleSoftwareUpdatesOnly': AppleSoftwareUpdatesOnly,
        'SoftwareUpdateServerURL': SoftwareUpdateServerURL,
        'DaysBetweenNotifications': DaysBetweenNotifications,
        'UseClientCertificate': UseClientCertificate,
        'SuppressUserNotification': SuppressUserNotification,
        'SuppressAutoInstall': SuppressAutoInstall,
        'SuppressStopButtonOnInstall': SuppressStopButtonOnInstall,
        'FollowHTTPRedirects': FollowHTTPRedirects,
        'UnattendedAppleUpdates': UnattendedAppleUpdates,
        'InstallRequiresLogout': InstallRequiresLogout,
        'LocalOnlyManifest': LocalOnlyManifest,
        'munkiprotocol': munkiprotocol,
    }])
    return report

def main():
    """Main"""
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Write munkiinfo report to cache
    output_plist = os.path.join(cachedir, 'munkiinfo.plist')
    plistlib.writePlist(munkiinfo_report(), output_plist)

if __name__ == "__main__":
    main()