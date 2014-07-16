#!/usr/bin/python
# extracts information about the external displays from system profiler

import sys
import os
import subprocess
import plistlib
import datetime

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

      #Type section
      try:
        if display.get('spdisplays_display-serial-number', None):
          result += 'Type = External'
        elif display['_spdisplays_display-vendor-id'] == "610":
          result += 'Type = Internal'
        else:
          result += 'Type = External'
      except KeyError, error: #this catches the error for 10.6 where there is no vendor for built-in displays
          result += 'Type = Internal'

      #Serial section
      if display.get('spdisplays_display-serial-number', None):
        result += '\nSerial = ' + str(display['spdisplays_display-serial-number'])
      else:
        result += '\nSerial = n/a'

      try:
        #Vendor section
        result += '\nVendor = ' + str(display['_spdisplays_display-vendor-id'])

        #Model section
        result += '\nModel = ' + str(display['_name'])
        makeValid = display['_spdisplays_display-week']
        if int(makeValid) == 255:
            result += ' (' + str(display['_spdisplays_display-year']) + ")"
            makeValid = "0"

        #Manufactured section
            pretty = datetime.datetime.strptime(display['_spdisplays_display-year'] + makeValid + '1', '%Y%W%w')
            result += '\nManufactured = ' + str(pretty.strftime('%B %Y'))

        #Native resolution section
        result += '\nNative = ' + str(display['_spdisplays_pixels'])

        #Save section
        result += '\n----------\n'

      except KeyError, error:
        result += '\nAn error ocurred while reading this display\n'

##############

# Write to disk
file = open("%s/displays.txt" % cachedir, "w")
file.write(result)
file.close()

exit(0)
