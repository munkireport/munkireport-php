#!/usr/bin/python
# extracts information about the external displays from system profiler

import sys
import os
import subprocess
import plistlib
import datetime
import time

# Skip manual check
if len(sys.argv) > 1:
  if sys.argv[1] == 'manualcheck':
    print 'Manual check: skipping'
    exit(0)

# Create cache dir if it does not exist
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
  os.makedirs(cachedir)

sp = subprocess.Popen(['system_profiler', '-xml', 'SPDisplaysDataType'], stdout=subprocess.PIPE)
out, err = sp.communicate()

plist = plistlib.readPlistFromString(out)

result = ''

#loop inside each graphic card
for vga in plist[0]['_items']:

  #this filters out iMacs with no external display
  if vga.get('spdisplays_ndrvs', None):

    #loop within each display
    for display in vga['spdisplays_ndrvs']:

      #filter out built-in that don't have serial (laptops)
      if display.get('spdisplays_display-serial-number', None):
        #Serial section
        result += 'Serial = ' + str(display['spdisplays_display-serial-number'])

        try:
          #Vendor section
          result += '\nVendor = ' + str(display['_spdisplays_display-vendor-id'])

          #Model section
          result += '\nModel = ' + str(display['_name'])

          #Manufactured section
          pretty = datetime.datetime.strptime(display['_spdisplays_display-year'] + display['_spdisplays_display-week'] + '1', '%Y%W%w')
          result += '\nManufactured = ' + str(pretty.strftime('%B %Y'))

          #Native resolution section
          result += '\nNative = ' + str(display['_spdisplays_pixels'])

        except KeyError as error:
          pass

      else:
        #built-in that don't have serial (laptops)
        result += 'No external display'

      result += '\n----------\n'

  else:
    # iMacs with no external display
    result += 'No external display'

# Write to disk
file = open("%s/displays.txt" % cachedir, "w")
file.write(result)
file.close()

exit(0)
