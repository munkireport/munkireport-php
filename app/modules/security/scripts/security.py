#!/usr/bin/python
"""
Extracts information about SIP, Gatekeeper, and users who have remote access.
"""

import os
import sys
import subprocess
import plistlib

def gatekeeper_check():
    """ Gatekeeper checks. Simply calls the spctl and parses status. Requires 10.7+"""

    if float(os.uname()[2][0:2]) >= 11:
        sp = subprocess.Popen(['spctl', '--status'], stdout=subprocess.PIPE)
        out, err = sp.communicate()
        if "enabled" in out:
            return "Active"
        else:
            return "Disabled"
    else:
        return "Not Supported"


def sip_check():
    """ SIP checks. We need to be running 10.11 or newer."""

    if float(os.uname()[2][0:2]) >= 15:
        sp = subprocess.Popen(['csrutil', 'status'], stdout=subprocess.PIPE)
        out, err = sp.communicate()
        if "enabled" in out:
            return "Active"
        else:
            return "Disabled"


def ssh_access_check():
    """Check for users who can log in via SSH using the built-in group. This will not
    cover any users who are granted SSH via an AD/OD group."""

    #Check first that SSH is enabled!
    sp = subprocess.Popen(['systemsetup', '-getremotelogin'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    if "Off" in out:
        return "SSH Disabled"

    # First we need to check if SSH is open to all users - if so, the ssh group will
    # named com.apple.access_ssh-disabled
    sp = subprocess.Popen(['dscl', '.', 'list', '/Groups'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    if "com.apple.access_ssh-disabled" in out:
        return "All users permitted"
    
    # Now that we know SSH is enabled and not open to all users, enumerate users.
    sp = subprocess.Popen(['dscl', '.', 'list', '/Users'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    user_list = out.split()
    ssh_users = ''

    for user in user_list:
        if user[0] in '_': # filter out system users that start with _
            continue
        else:
            sp = subprocess.Popen(['dsmemberutil', 'checkmembership', '-U', user, '-G', 'com.apple.access_ssh'], stdout=subprocess.PIPE)
            out, err = sp.communicate()
            if 'is a member' in out:
                ssh_users += user + ' '
            else:
                pass
    return ssh_users.strip()


def ard_access_check():
    """Check for local users who have ARD permissions
    First we need to check if all users are allowed. If not, we look for granular permissions.
    There are two methods to do this. One is to look for the bitmask in navprivs
    in the local directory. The second is to read the same bitmask from the
    plist below. I'm not entirely sure which one is more accurate so for now
    I'm going to include both and refactor later.
    Thanks to @frogor and @foigus for their work on the bitmask and for example code."""

    # Zeroth method: check if all users are permitted.
    # Thank you to @steffan for pointing out this plist key!
    plist_path = '/Library/Preferences/com.apple.RemoteManagement.plist'

    # plist lib likes to barf on binary plists, so hack around it by converting to xml
    # TODO - make this not hacky...
    sp = subprocess.Popen(['plutil', '-convert', 'xml1', plist_path])
    out, err = sp.communicate()
    plist = plistlib.readPlist(plist_path)

    if plist['ARD_AllLocalUsers']:
        return "All users permitted"
    else:
        # First method: local directory
        sp = subprocess.Popen(['dscl', '.', '-list', '/Users'], stdout=subprocess.PIPE)
        out, err = sp.communicate()

        user_list = out.split()

        for user in user_list:
            if user[0] in '_':
                continue
            else:
                args = '/Users/' + user
                sp = subprocess.Popen(['dscl', '.', '-read', args, 'naprivs'], stderr=subprocess.PIPE, stdout=subprocess.PIPE)
                out, err = sp.communicate()
            if out not in 'No such key':
                bitmask = out.split()
                permissions_bitmask(bitmask[1])
            else:
                pass

        plist_path = '/Library/Application Support/Apple/Remote Desktop/Client/Settings.plist'
        plist = plistlib.readPlist(plist_path)

        ard_users = ''

        for entry in plist['AuthSharedSecretList']:
            user = plist['AuthSharedSecretList'][entry][2]
            bitmask = plist['AuthSharedSecretList'][entry][1]
            permissions_bitmask(bitmask)
            ard_users += user + ' '

        # Sometimes the plist will have duplicate entries - only return each username once.
        # We do this by putting into a set which forces things to be unique and then joining
        # back to a string.
        unique_ard_users = set(ard_users.split())
        return " ".join(unique_ard_users)


def permissions_bitmask(user_mask):
    """Takes a given bitmask from the plist and calculates the permissions the user has.
    The plist structure is some sort of hash for each user as the key, followed by a
    password hash, the bitmask, and the short username."""

    permissions = {
        "kPrivSTUserHasAccess":   0x80000000,
        "kPrivSTTextMessages":    0x00000001,
        "kPrivSTControlObserve":  0x00000002,
        "kPrivSTSendFiles":       0x00000004,
        "kPrivSTDeleteFiles":     0x00000008,
        "kPrivSTGenerateReports": 0x00000010,
        "kPrivSTOpenQuitApps":    0x00000020,
        "kPrivSTChangeSettings":  0x00000040,
        "kPrivSTRestartShutDown": 0x00000080,
        "kPrivSTObserveOnly":     0x00000100,
        "kPrivSTShowObserve":     0x40000000,
    }

    perms = set()
    for n, m in permissions.items():
        if m & int(user_mask):
            perms.add(n)
    return


def firmware_pw_check():
    """Checks to see if a firmware password is set"""
    sp = subprocess.Popen(['firmwarepasswd', '-check'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    return out.split()[2]


def main():
    """main"""

    # Skip running on manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)

    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Create an empty directory object to hold results from check methods, then run.
    result = {}
    result.update({'gatekeeper': gatekeeper_check()})
    result.update({'sip': sip_check()})
    result.update({'ssh_users': ssh_access_check()})
    result.update({'ard_users': ard_access_check()})
    result.update({'firmwarepw': firmware_pw_check()})

    # Write results of checks to cache file
    output_plist = os.path.join(cachedir, 'security.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
