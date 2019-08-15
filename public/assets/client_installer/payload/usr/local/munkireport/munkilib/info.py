# encoding: utf-8
#
# Copyright 2009-2019 Greg Neagle.
#
# Licensed under the Apache License, Version 2.0 (the 'License');
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#      https://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an 'AS IS' BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
"""
info.py

Created by Greg Neagle on 2016-12-14.

Utilities that retrieve information from the current machine.
"""
# standard libs
import ctypes
import ctypes.util
import fcntl
import os
import select
import struct
import subprocess
import sys

# Apple's libs
import LaunchServices
# PyLint cannot properly find names inside Cocoa libraries, so issues bogus
# No name 'Foo' in module 'Bar' warnings. Disable them.
# pylint: disable=E0611
from Foundation import NSMetadataQuery, NSPredicate, NSRunLoop
from Foundation import NSDate, NSTimeZone
# pylint: enable=E0611

# our libs
from . import FoundationPlist


# Always ignore these directories when discovering applications.
APP_DISCOVERY_EXCLUSION_DIRS = set([
    'Volumes', 'tmp', '.vol', '.Trashes', '.MobileBackups', '.Spotlight-V100',
    '.fseventsd', 'Network', 'net', 'home', 'cores', 'dev', 'private',
    ])


class Error(Exception):
    """Class for domain specific exceptions."""


class TimeoutError(Error):
    """Timeout limit exceeded since last I/O."""


def get_hardware_info():
    '''Uses system profiler to get hardware info for this machine'''
    cmd = ['/usr/sbin/system_profiler', 'SPHardwareDataType', '-xml']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, dummy_error) = proc.communicate()
    try:
        plist = FoundationPlist.readPlistFromString(output)
        # system_profiler xml is an array
        sp_dict = plist[0]
        items = sp_dict['_items']
        sp_hardware_dict = items[0]
        return sp_hardware_dict
    except BaseException:
        return {}



if __name__ == '__main__':
    print 'This is a library of support tools for the Munki Suite.'
