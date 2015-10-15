#!/usr/bin/python
"""
extracts information about the external displays from system profiler
"""

import sys
import csv
import os
import re
from datetime import datetime

def cp_date_to_unixtimestamp(cp_date):
    """ Convert Crashplan date to unix timestamp """
    dt = datetime.strptime(cp_date, "%m/%d/%y %I:%M%p")
    #ep = dt.fromtimestamp(0)
    #diff = dt - ep
    #return int(diff.total_seconds())
    return int(datetime.strftime(dt, "%s"))

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)

crashplan_log="/Library/Logs/CrashPlan/history.log.0"
cacheFile = 'crashplan.txt'

# crashplan logformat
regex = re.compile(r'. (\d+\/\d+\/\d+ \d+:\d+[AP]M)\s+([^\s]+)\s+(.*)')

start = 0
destinations = {}
if os.path.exists(crashplan_log):
    with open(crashplan_log, mode='r', buffering=-1) as cplog:
        for line in cplog:
            m = regex.match(line)
            if m:
                timestamp = cp_date_to_unixtimestamp(m.group(1))
                destination = m.group(2)
                message = m.group(3)
                # Check if destination is enclosed with []
                if not re.match(r'^\[.+\]$', destination):
                    continue
                if not destinations.get(destination):
                    destinations[destination] = {'destination': destination, 'start': 0, 'last_success': 0, 'duration': 0, 'last_failure': 0, 'reason': ''}
                if re.match(r'^Starting backup', message):
                    destinations[destination]['start'] = timestamp
                elif re.match(r'^Completed backup', message):
                    if destinations[destination]['start']:
                        duration = timestamp - destinations[destination]['start']
                        destinations[destination]['duration'] = duration
                    else:
                        destinations[destination]['duration'] = 0
                    destinations[destination]['last_success'] = timestamp
                elif re.match(r'^Stopped backup', message):
                    destinations[destination]['last_failure'] = timestamp
                    reason = re.match(r'.*Reason for stopping backup: (.*)', next(cplog))
                    if reason:
                        destinations[destination]['reason'] = reason.group(1)
                    else:
                        destinations[destination]['reason'] = 'unknown'
else:
    print "CrashPlan log not found here: %s " % crashplan_log
    
# Make sure cachedir exists
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

# Write to file
listWriter = csv.DictWriter(
   open(os.path.join(cachedir, cacheFile), 'wb'),
   fieldnames=['destination', 'start', 'last_success', 'duration', 'last_failure', 'reason'],
   delimiter=',',
   quotechar='"',
   quoting=csv.QUOTE_MINIMAL
)

listWriter.writeheader()
for name, values in destinations.iteritems():
    listWriter.writerow(values)
