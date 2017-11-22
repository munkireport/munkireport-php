#!/usr/bin/python
"""
munki_facts for munkireport
"""

import os
import json
import plistlib
import CoreFoundation
from datetime import datetime

class DateTimeEncoder(json.JSONEncoder):
    def default(self, o):
        if isinstance(o, datetime):
            return o.isoformat()

        return json.JSONEncoder.default(self, o)

def gather_facts(path):
  """
  Read fact keys and values from the ManagedInstallReport and process them into a
  dict of strings less than 256 characters.
  """
  try:
    plist = plistlib.readPlist(path)
  except:
    print 'Could not find ManagedInstallReport.plist'
    return {}

  facts = {}

  if plist.get('Conditions'):
    for key, value in plist['Conditions'].iteritems():
      if key == 'date':
          continue
      key = key[:65535]
      if isinstance(value, list) and len(value) > 0:
        facts[key] = json.dumps(value)[:65535]
      else:
        facts[key] = str(value)[:65535]
  else:
    print 'No facts found.'

  return facts


def main():
  """Main"""

  # Create cache dir if it does not exist
  cache_dir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
  if not os.path.exists(cache_dir):
    os.makedirs(cache_dir)

  managed_install_dir = CoreFoundation.CFPreferencesCopyAppValue( "ManagedInstallDir", "ManagedInstalls")
  managed_install_dir = managed_install_dir or '/Library/Managed Installs'
  managed_install_report = managed_install_dir + '/ManagedInstallReport.plist'

  if not os.path.exists(managed_install_report):
    print "%s is missing." % managed_install_report
    facts_report = {}
  else:
    facts_report = gather_facts(managed_install_report)

  # Write munkiinfo report to cache
  output_plist = os.path.join(cache_dir, 'munki_facts.plist')
  plistlib.writePlist(facts_report, output_plist)

if __name__ == '__main__':
  main()
