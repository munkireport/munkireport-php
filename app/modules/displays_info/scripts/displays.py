#!/usr/bin/python
# extracts monitor and VGA information from system profiler

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

  #this filters iMacs with no external monitor
  if vga.get('spdisplays_ndrvs', None):

    #loop within each monitor
    for monitor in vga['spdisplays_ndrvs']:

      #catch KeyError for built-in displays and 10.6. todo Only?
      try:
        #Vendor section
        #todo move vendor matching to mrphp:'610' Apple, '10ac' DELL...
				#todo find a source for this? who is '4c2d' or '30e4' ?
        result += 'Vendor = ' + str(monitor['_spdisplays_display-vendor-id'])

        #Model section
        result += '\nModel = ' + str(monitor['_name'])

        #Manufactured section
				#todo this was good for displaying. Now should be unix time for mrphp!
        pretty = datetime.datetime.strptime(monitor['_spdisplays_display-year'] + monitor['_spdisplays_display-week'] + '1', '%Y%W%w')
        pretty = pretty.strftime('%B %Y')
        result += '\nManufactured = ' + pretty

        #Native resolution section
        result += '\nNative = ' + str(monitor['_spdisplays_pixels'])

        #Type section
        if monitor.get('spdisplays_builtin', None):
          result += '\nType = Internal '
        else:
          result += '\nType = External '

        #Serial section
        #built-in don't have a serial. We need to protect this
        if monitor.get('spdisplays_display-serial-number', None):
          result += '\nSerial = ' + str(monitor['spdisplays_display-serial-number'])
        else:
          result += '\nSerial = n/a'
        result += '\n-----------------\n'

      except KeyError as error:
        #todo something here?
        pass

  # else:
  #   # iMacs with no external monitor
  #   #todo something here?
  #   pass

  #VGA model
  result += 'VGA = ' + str(vga['sppci_model'])
  result += '\nVram = ' + str(vga['spdisplays_vram']) + '\n'

# quality control. todo remove this
# print result

# Write to disk
file = open("%s/display.txt" % cachedir, "w")
file.write(result)
file.close()

exit(0)
