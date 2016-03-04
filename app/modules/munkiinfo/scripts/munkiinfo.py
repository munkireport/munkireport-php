#!/usr/bin/python
"""
munkiinfo for munkireport
"""

import os
import plistlib
import sys
import urlparse
from Foundation import CFPreferencesCopyAppValue

sys.path.append('/usr/local/munki')
try:
    from munkilib import munkicommon
except ImportError, msg:
    print "%s" % msg
    exit(1)

def pref_to_str(pref_value):
    """If the value of a preference is None return an empty string type
    so we can write this data to a plist. Convert Bool values to strings
    for easy display in MunkiReport."""
    if pref_value is None:
        # convert to empty string for values that are not set
        pref_value = ''
    elif pref_value is True:
        pref_value = 'True'
    elif pref_value is False:
        pref_value = 'False'
    else:
        return pref_value
    return pref_value

def munki_prefs():
    """A full listing of all Munki preferences"""
    our_prefs = [
        'AppleSoftwareUpdatesOnly',
        'InstallAppleSoftwareUpdates',
        'UnattendedAppleUpdates',
        'SoftwareUpdateServerURL',
        'SoftwareRepoURL',
        'PackageURL',
        'CatalogURL',
        'ManifestURL',
        'IconURL',
        'ClientResourceURL',
        'ClientResourcesFilename',
        'HelpURL',
        'ClientIdentifier',
        'ManagedInstallDir',
        'LogFile',
        'LogToSyslog',
        'LoggingLevel',
        'DaysBetweenNotifications',
        'UseClientCertificate',
        'UseClientCertificateCNAsClientIdentifier',
        'SoftwareRepoCAPath',
        'SoftwareRepoCACertificate',
        'ClientCertificatePath',
        'ClientKeyPath',
        # 'AdditionalHttpHeaders',
        'PackageVerificationMode',
        'SuppressUserNotification',
        'SuppressAutoInstall',
        'SuppressLoginwindowInstall',
        'SuppressStopButtonOnInstall',
        'InstallRequiresLogout',
        'ShowRemovalDetail',
        'MSULogEnabled',
        'MSUDebugLogEnabled',
        'LocalOnlyManifest',
        'FollowHTTPRedirects',
    ]
    return our_prefs

def formated_prefs():
    """Formated dictionary object for output to plist"""
    my_dict = {}
    for pref in munki_prefs():
        pref_value = pref_to_str(munkicommon.pref(pref))
        my_dict.update({pref: pref_value})
    return my_dict

def get_munkiprotocol():
    """The protocol munki is using"""
    software_repo_url = pref_to_str(munkicommon.pref('SoftwareRepoURL'))
    try:
        url_parse = urlparse.urlparse(software_repo_url)
        return url_parse.scheme
    except AttributeError:
        return 'Could not obtain protocol'

def munkiinfo_report():
    """Build our report data for our munkiinfo plist"""
    munkiprotocol = get_munkiprotocol()
    if 'file' in munkiprotocol:
        munkiprotocol = 'localrepo'

    AppleCatalogURL = str(CFPreferencesCopyAppValue('CatalogURL', 'com.apple.softwareupdate'))
    if AppleCatalogURL == 'None':
        AppleCatalogURL = ''

    report = {
        'AppleCatalogURL': AppleCatalogURL,
        'munkiprotocol': munkiprotocol,
    }
    report.update(formated_prefs())
    return ([report])

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
