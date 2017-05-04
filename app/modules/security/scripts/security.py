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
    else:
        return "Not Supported"


def ssh_access_check():
    """Check for users who can log in via SSH using the built-in group. This will not
    cover any users who are granted SSH via an AD/OD group."""

    #Check first that SSH is enabled!
    sp = subprocess.Popen(['systemsetup', '-getremotelogin'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    if "Off" in out:
        return "SSH Disabled"

    else:
        # First we need to check if SSH is open to all users. A few ways  to tell:
        # -on 10.8 and older, systemsetup will show as on but the access_ssh groups are not present
        # -on 10.9, systemsetup will show as on and list all users in access_ssh
        # -on 10.10 and newer, systemsetup will show as on and access_ssh-disabled will be present
        # Note for 10.10 and newer - root will show up as authorized if systemsetup was used to turn
        # on SSH, and not if pref pane was used.

        sp = subprocess.Popen(['dscl', '.', 'list', '/Groups'], stdout=subprocess.PIPE)
        out, err = sp.communicate()

        if "com.apple.access_ssh-disabled" in out:
            # if this group exists, all users are permitted to access SSH
            return "All users permitted"

        elif "com.apple.access_ssh" in out:
            # if this group exists, SSH is enabled but only some users are permitted
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

        else:
            # if neither SSH group exists but SSH is enabled, it was turned on with
            # systemsetup and all users are enabled.
            return "All users permitted"

def ard_access_check():
    """Check for local users who have ARD permissions
    First we need to check if all users are allowed. If not, we look for granular permissions
    in the directory. Thank you @frogor and @foigus for help on the directory part."""

    # First method: check if all users are permitted.
    # Thank you to @steffan for pointing out this plist key!
    plist_path = '/Library/Preferences/com.apple.RemoteManagement.plist'
    if os.path.exists(plist_path):
        # plist lib likes to barf on binary plists, so hack around it by converting to xml
        # TODO - use foundationplist to avoid conversion
        sp = subprocess.Popen(['plutil', '-convert', 'xml1', plist_path])
        out, err = sp.communicate()
        plist = plistlib.readPlist(plist_path)

        if plist.get('ARD_AllLocalUsers', None):
            return "All users permitted"
        else:
            # Second method - check local directory for naprivs
            sp = subprocess.Popen(['dscl', '.', '-list', '/Users'], stdout=subprocess.PIPE)
            out, err = sp.communicate()

            user_list = out.split()
            ard_users = []
            for user in user_list:
                if user[0] in '_':
                    continue
                else:
                    args = '/Users/' + user
                    sp = subprocess.Popen(['dscl', '.', '-read', args, 'naprivs'], stderr=subprocess.PIPE, stdout=subprocess.PIPE)
                    out, err = sp.communicate()
                if out not in 'No such key':
                    ard_users.append(user)
                else:
                    pass
            return " ".join(ard_users)
    else:
        # plist_path does not exist, which indicates that ARD is not enabled.
        return "ARD Disabled"


def firmware_pw_check():
    """Checks to see if a firmware password is set.
    The command firmwarepassword appeared in 10.10, so we use nvram for older versions.
    Thank you @steffan for this check."""
    if float(os.uname()[2][0:2]) >= 14:
        sp = subprocess.Popen(['firmwarepasswd', '-check'], stdout=subprocess.PIPE)
        out, err = sp.communicate()
        firmwarepw = out.split()[2]

    else:
        sp = subprocess.Popen(['nvram', 'security-mode'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        mode_out, mode_err = sp.communicate()

        if "none" in mode_out or "Error getting variable" in mode_err:
            firmwarepw = "No"
        else:
            firmwarepw = "Yes"

    return firmwarepw


def firewall_enable_check():
    """Checks to see if firewall is enabled using a one-shot defaults read command.
    Doing it this way because shelling out is easier than having to convert..."""
    
    sp = subprocess.Popen(['defaults', 'read', '/Library/Preferences/com.apple.alf.plist', 'globalstate'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    if "1" in out:
        firewall_enabled = "Enabled"
    elif "0" in out:
        firewall_enabled = "Disabled"
    else:
        firewall_enabled = "Error"

    return firewall_enabled


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
    result.update({'firewall':firewall_enable_check()})

    # Write results of checks to cache file
    output_plist = os.path.join(cachedir, 'security.plist')
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
