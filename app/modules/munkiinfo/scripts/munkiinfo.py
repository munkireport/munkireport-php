#!/usr/bin/python

# Checks for http or https protocol

from Foundation import CFPreferencesCopyAppValue
import os
import sys
sys.path.append('/usr/local/munki/munkilib/')
import munkicommon

def get_munkiprotocol_status():
	Protocol = munkicommon.pref('SoftwareRepoURL')
	try:
		return Protocol.split(":")[0]
	except Exception:
		Protocol = 'Could not obtain protocol'
		return Protocol
		
def main():
	try:
		# Create cache dir if it does not exist
		cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
		if not os.path.exists(cachedir):
			os.makedirs(cachedir)
		# Write to disk
		result = get_munkiprotocol_status()
		txtfile = open("%s/munkiinfo.txt" % cachedir, "w")
		txtfile.write(result.encode('utf-8'))
		txtfile.close()
	except:
		print 'Could not read SoftwareRepoURL'
		sys.exit(1)

if __name__ == "__main__":
	main()