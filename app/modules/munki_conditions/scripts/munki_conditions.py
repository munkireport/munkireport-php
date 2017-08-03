#!/usr/bin/python
"""
munki_conditions for munkireport
"""

import os
import sys
import plistlib
import operator
from SystemConfiguration import SCDynamicStoreCopyConsoleUser


MUNKIPATH = '/usr/local/munki/'
CACHEPATH = MUNKIPATH + 'preflight.d/cache/'
USERSESSIONSPATH = CACHEPATH + 'user_sessions.plist'
REPORTPATH = '/Library/Managed Installs/ManagedInstallReport.plist'

def gather_conditions():
  try:
    plist = plistlib.readPlist(REPORTPATH)
  except:
    print 'Could not find ManagedInstallReport.plist'
    plist = {'Conditions': []}

  conditions = {}

  if plist.get('Conditions'):
    for key, value in plist['Conditions'].iteritems():
      if isinstance(value, list) and len(value) > 0:
        conditions[key] = reduce((lambda x, y: x + ', ' + str(y)), value)
      else:
        conditions[key] = str(value)
  else:
    print 'No conditions found.'

  return conditions

def munki_conditions_report():
  return [gather_conditions()]

def main():
  """Main"""
  # Create cache dir if it does not exist
  cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
  if not os.path.exists(cachedir):
      os.makedirs(cachedir)

  # Write munkiinfo report to cache
  output_plist = os.path.join(CACHEPATH, 'munki_conditions.plist')
  plistlib.writePlist(munki_conditions_report(), output_plist)

if __name__ == '__main__':
  main()