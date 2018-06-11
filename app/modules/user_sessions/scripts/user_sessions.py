#!/usr/bin/python
"""
Parse user sessions on macOS so we can determine what users logged in and
when the event took place. We only obtain 'console' and 'ssh' sessions as
regular 'ttys' sessions are less useful as a whole.

Author: Clayton Burlison - https://clburlison.com

Code from: Michael Lynn -
    https://gist.github.com/pudquick/7fa89716fe2a8f6cdc084958671b7b58

Created for MunkiReport - https://github.com/munkireport/munkireport-php
"""

from ctypes import (CDLL,
                    Structure,
                    POINTER,
                    c_int64,
                    c_int32,
                    c_int16,
                    c_char,
                    c_uint32)
from ctypes.util import find_library
import plistlib
import os
import sys
import time
import subprocess

# constants
c = CDLL(find_library("System"))
BOOT_TIME = 2
USER_PROCESS = 7
DEAD_PROCESS = 8
SHUTDOWN_TIME = 11


class timeval(Structure):
    _fields_ = [
                ("tv_sec",  c_int64),
                ("tv_usec", c_int32),
               ]


class utmpx(Structure):
    _fields_ = [
                ("ut_user", c_char*256),
                ("ut_id",   c_char*4),
                ("ut_line", c_char*32),
                ("ut_pid",  c_int32),
                ("ut_type", c_int16),
                ("ut_tv",   timeval),
                ("ut_host", c_char*256),
                ("ut_pad",  c_uint32*16),
               ]


def get_uid(username):
    cmd = ['/usr/bin/id', '-u', username]
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    output = output.strip()
    return output

def fast_last(session='gui_ssh'):
    """This method will replicate the functionallity of the /usr/bin/last
    command to output all logins, reboots, and shutdowns. We then calculate
    the logout.

    session takes on of the following strings:
        * gui
        * gui_ssh
        * all
    """
    # local constants
    setutxent_wtmp = c.setutxent_wtmp
    setutxent_wtmp.restype = None
    getutxent_wtmp = c.getutxent_wtmp
    getutxent_wtmp.restype = POINTER(utmpx)
    endutxent_wtmp = c.setutxent_wtmp
    endutxent_wtmp.restype = None
    # data storage
    events = []
    # initialize
    setutxent_wtmp(0)
    entry = getutxent_wtmp()
    while entry:
        e = entry.contents
        entry = getutxent_wtmp()
        event = {}
        if e.ut_type == BOOT_TIME:
            # reboot/startup
            event = {'event': 'reboot',
                     'time': e.ut_tv.tv_sec}
        elif e.ut_type == SHUTDOWN_TIME:
            # shutdown
            event = {'event': 'shutdown',
                     'time': e.ut_tv.tv_sec}
        elif (e.ut_type == USER_PROCESS) or (e.ut_type == DEAD_PROCESS):
            if e.ut_type == USER_PROCESS: event_label = 'login'
            if e.ut_type == DEAD_PROCESS: event_label = 'logout'
            if session is 'gui' and e.ut_line != "console":
                continue
            if (session is 'gui_ssh' and e.ut_host == "") and (
                    session is 'gui_ssh' and e.ut_line != "console"):
                continue
            event = {'event': event_label,
                     'user': e.ut_user,
                     'time': e.ut_tv.tv_sec}
                     
            if e.ut_user != "":
            	event['uid'] = get_uid(e.ut_user)
            if e.ut_host != "":
                event['remote_ssh'] = e.ut_host

        if event != {}:
            events.append(event)
    # finish
    endutxent_wtmp()
    return events


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
    result = fast_last()

    # Write user session results to cache
    output_plist = os.path.join(cachedir, 'user_sessions.plist')
    plistlib.writePlist(result, output_plist)


if __name__ == "__main__":
    main()
