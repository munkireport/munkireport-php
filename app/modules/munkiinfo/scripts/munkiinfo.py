#!/usr/bin/python

from Foundation import CFPreferencesCopyAppValue
import os
import plistlib
import sys
try:
    from munkilib import FoundationPlist
except:
    sys.path.append('/usr/local/munki')
    from munkilib import FoundationPlist

sys.path.append('/usr/local/munki/munkilib/')
import munkicommon

def get_munkiprotocol_status():
	Protocol = munkicommon.pref('SoftwareRepoURL')
	try:
		return Protocol.split(":")[0]
	except Exception:
		Protocol = 'Could not obtain protocol'
		return Protocol

def get_munki_version():
	munkireport = '/Library/Managed Installs/ManagedInstallReport.plist'
	try:
		plist = FoundationPlist.readPlist(munkireport)
		munkiversion = plist['Conditions']['munki_version']
		return munkiversion
	except Exception:
		return {}		
		
def munkiinfo_report():
	report = []
	if (len(sys.argv) > 1):
		runtype = sys.argv[1]
	else:
		runtype = 'custom'
	munkiprotocol = get_munkiprotocol_status()
	munkiversion = get_munki_version()
	if "file" in munkiprotocol:
		munkiprotocol = 'localrepo'
	report.append({
	'munkiprotocol': munkiprotocol, 
	'runstate': 'done', 
	'runtype': runtype, 
	'starttime': 'NEEDTOFINISH', 
	'endtime': 'NEEDTOFINISH', 
	'version': munkiversion
	})
	return report

def main():
	try:
		# Create cache dir if it does not exist
		cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
		if not os.path.exists(cachedir):
			os.makedirs(cachedir)
		# Write munkiinfo report to cache
		plistlib.writePlist(munkiinfo_report(), "%s/munkiinfo.plist" % cachedir)
	except:
		print 'Could not successfully create plist'
		sys.exit(1)

if __name__ == "__main__":
	main()