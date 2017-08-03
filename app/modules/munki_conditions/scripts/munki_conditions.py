#!/usr/bin/python
"""
munki_conditions for munkireport
"""

import os
import sys
import plistlib

#todo: get report path from preferences.

CACHEPATH = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
REPORTPATH = '/Library/Managed Installs/ManagedInstallReport.plist'

def gather_conditions():
  """
  Read condition keys and values from the ManagedInstallReport and process them into a
  dict of strings less than 256 characters.
  """
  try:
    plist = plistlib.readPlist(REPORTPATH)
  except:
    print 'Could not find ManagedInstallReport.plist'
    return {}

  conditions = {}

  if plist.get('Conditions'):
    for key, value in plist['Conditions'].iteritems():
      key = key[:255]
      if isinstance(value, list) and len(value) > 0:
        conditions[key] = ', '.join([str(x) for x in value])[:255]
      else:
        conditions[key] = str(value)[:255]
  else:
    print 'No conditions found.'

  return conditions

def main():
  """Main"""

  # Create cache dir if it does not exist
  if not os.path.exists(CACHEPATH):
      os.makedirs(CACHEPATH)

  # Write munkiinfo report to cache
  output_plist = os.path.join(CACHEPATH, 'munki_conditions.plist')
  plistlib.writePlist(gather_conditions(), output_plist)

if __name__ == '__main__':
  main()