#!/usr/bin/python
"""Enables location services (LS) globally,
Give Python access to location services,
Prints the current location to a plist.

Author: Clayton Burlison <https://clburlison.com>
Created: Jan. 25, 2016

Based off of works by:
@arubdesu - https://gist.github.com/arubdesu/b72585771a9f606ad800
@pudquick - https://gist.github.com/pudquick/c7dd1262bd81a32663f0
            https://gist.github.com/pudquick/329142c1740500bd3797
@lindes   - https://github.com/lindes/get-location/
University of Utah, Marriott Library -
            https://github.com/univ-of-utah-marriott-library-apple/privacy_services_manager
"""

import sys
import os
import plistlib
import platform
import subprocess
import tempfile
import json
from urllib2 import urlopen, URLError, HTTPError
from time import gmtime, strftime, sleep
from Foundation import CFPreferencesCopyAppValue
import objc

# PyLint cannot properly find names inside Cocoa libraries, so issues bogus
# No name 'Foo' in module 'Bar' warnings. Disable them.
# pylint: disable=E0611
from CoreLocation import CLLocationManager, kCLDistanceFilterNone, kCLLocationAccuracyBest
from Foundation import NSRunLoop, NSDate, NSObject
from Foundation import NSBundle
try:
    sys.path.append('/usr/local/munki/munkilib/')
    import FoundationPlist
except ImportError as error:
    print "Could not find FoundationPlist, are munkitools installed?"
    raise error

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        print 'Manual check: skipping'
        exit(0)

# Create cache dir if it does not exist
# pylint: disable=C0103
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

# Define location.plist
location = cachedir + "/location.plist"

plist = []

# Retrieve system UUID
IOKit_bundle = NSBundle.bundleWithIdentifier_('com.apple.framework.IOKit')

functions = [("IOServiceGetMatchingService", b"II@"),
             ("IOServiceMatching", b"@*"),
             ("IORegistryEntryCreateCFProperty", b"@I@@I"),
            ]

objc.loadBundleFunctions(IOKit_bundle, globals(), functions)

def io_key(keyname):
    """Pythonic function to retrieve system info without a subprocess call."""
    return IORegistryEntryCreateCFProperty(IOServiceGetMatchingService(0, \
           IOServiceMatching("IOPlatformExpertDevice")), keyname, None, 0)

def get_hardware_uuid():
    """Returns the system UUID."""
    return io_key("IOPlatformUUID")

def root_check():
    """Check for root access."""
    if not os.geteuid() == 0:
        exit("This must be run with root access.")

def os_vers():
    """Retrieve OS version."""
    maj_os_vers = platform.mac_ver()[0].split('.')[1]
    return maj_os_vers

def os_check():
    """Only tested on 10.8 - 10.11."""
    if not 8 <= int(os_vers()) <= 11:
        global plist
        plist = dict(
            CurrentStatus="Your OS is not supported at this time: %s." % platform.mac_ver()[0],
            LastRun=str(current_time_GMT()),
        )
        plistlib.writePlist(plist, location)
        exit("This tool only tested on 10.8 - 10.11")

def current_time_GMT():
    """Prints current date/time stamp"""
    currentTime = strftime("%Y-%m-%d %H:%M:%S +0000", gmtime())
    return currentTime

def service_handler(action):
    """Loads/unloads System's location services launchd job."""
    launchctl = ['/bin/launchctl', action,
                 '/System/Library/LaunchDaemons/com.apple.locationd.plist']
    subprocess.check_output(launchctl)

def sysprefs_boxchk():
    """Enables location services in sysprefs globally."""
    uuid = get_hardware_uuid()
    perfdir = "/private/var/db/locationd/Library/Preferences/ByHost/"
    if not os.path.exists(perfdir):
        os.makedirs(perfdir)
    path_stub = "/private/var/db/locationd/Library/Preferences/ByHost/com.apple.locationd."
    das_plist = path_stub + uuid.strip() + ".plist"
    try:
        on_disk = FoundationPlist.readPlist(das_plist)
    except:
        p = {}
        FoundationPlist.writePlist(p, das_plist)
        on_disk = FoundationPlist.readPlist(das_plist)
    val = on_disk.get('LocationServicesEnabled', None)
    if val != 1:
        service_handler('unload')
        on_disk['LocationServicesEnabled'] = 1
        FoundationPlist.writePlist(on_disk, das_plist)
        os.chown(das_plist, 205, 205)
        service_handler('load')

def add_python():
    """Python dict for clients.plist in locationd settings."""
    auth_plist = {}
    current_os = int(os_vers())
    domain = 'org.python.python'
    binary_path = ('/System/Library/Frameworks/Python.framework/'
                   'Versions/2.7/Resources/Python.app/Contents/MacOS/Python')

    if current_os == 11:
        domain = "com.apple.locationd.executable-%s" % binary_path

    das_plist = '/private/var/db/locationd/clients.plist'
    try:
        clients_dict = FoundationPlist.readPlist(das_plist)
    except:
        p = {}
        FoundationPlist.writePlist(p, das_plist)
        clients_dict = FoundationPlist.readPlist(das_plist)
    val = clients_dict.get(domain, None)
    need_to_run = False
    try:
        if val["Authorized"] != True:
            need_to_run = True
    except TypeError:
        need_to_run = True
    except KeyError:
        need_to_run = True

    # El Capital added a cdhash requirement that is difficult to calculate. As such we are allowing
    # the system to correctly input the values and then giving Python access to LS.
    if need_to_run is True:
        service_handler('unload')
        clients_dict[domain] = auth_plist
        FoundationPlist.writePlist(clients_dict, das_plist)
        os.chown(das_plist, 205, 205)
        service_handler('load')
        lookup(1)
        service_handler('unload')
        clients_dict = FoundationPlist.readPlist(das_plist)
        auth_plist = clients_dict[domain]
        auth_plist["Authorized"] = True
        FoundationPlist.writePlist(clients_dict, das_plist)
        os.chown(das_plist, 205, 205)
        service_handler('load')
        # sleep(30)
        # Munki's preflight will time out before we've had 30 seconds to enable
        # all the services needed. As such we will exit gracefully.
        # This process will only happen at initial setup or if location services was disabled.
        exit("We have enabled Location Services." +
             "Please wait 30 seconds before tying to do a lookup.")

# Access CoreLocation framework to locate Mac
is_enabled = CLLocationManager.locationServicesEnabled()
is_authorized = CLLocationManager.authorizationStatus()

class MyLocationManagerDelegate(NSObject):
    """CoreLocation delegate for handling location lookups. This class
    is required for python to properly start/stop lookups with location
    services.
    """
    def init(self):
        """Define location manager settings for lookups."""
        self = super(MyLocationManagerDelegate, self).init()
        if not self:
            return
        self.locationManager = CLLocationManager.alloc().init()
        self.locationManager.setDelegate_(self)
        self.locationManager.setDistanceFilter_(kCLDistanceFilterNone)
        self.locationManager.setDesiredAccuracy_(kCLLocationAccuracyBest)
        self.locationManager.startUpdatingLocation()
        return self
    def locationManager_didUpdateToLocation_fromLocation_(self, manager, newloc, oldloc):
        """Splits location data into separate pieces for processing later."""
        lat = newloc.coordinate().latitude
        lon = newloc.coordinate().longitude
        verAcc = newloc.verticalAccuracy()
        horAcc = newloc.horizontalAccuracy()
        altitude = newloc.altitude()
        time = newloc.timestamp()
        gmap = ("http://www.google.com/maps/place/" + str(lat) + "," + str(lon) +
                "/@" + str(lat) + "," + str(lon) + ",18z/data=!3m1!1e3")

        global plist
        plist = dict(
            Latitude=str(lat),
            Longitude=str(lon),
            LatitudeAccuracy=int(verAcc),
            LongitudeAccuracy=int(horAcc),
            Altitude=int(altitude),
            GoogleMap=str(gmap),
            LastRun=str(time),
            CurrentStatus="Successful",
            LS_Enabled=is_enabled,
        )
    def locationManager_didFailWithError_(self, manager, err):
        """Handlers errors for location manager."""
        if is_enabled is True:
            if is_authorized == 3:
                status = "Unable to locate"
            if is_authorized == 2:
                status = "Denied"
            if is_authorized == 1:
                status = "Restricted"
            if is_authorized == 0:
                status = "Not Determined"
        else:
            status = "Location Services Disabled"

        global plist
        plist = dict(
            CurrentStatus="Unsuccessful: " + status,
            LS_Enabled=is_enabled,
            LastRun=str(current_time_GMT()),
        )

def lookup(lookupTime):
    """Ask python to find current location."""
    finder = MyLocationManagerDelegate.alloc().init()
    NSRunLoop.currentRunLoop().runUntilDate_(NSDate.dateWithTimeIntervalSinceNow_(lookupTime))

def download_file(url):
    """Download a simple file from the ineternet."""
    try:
        temp_file = os.path.join(tempfile.mkdtemp(), 'tempdata')
        f = urlopen(url)
        with open(temp_file, "wb") as local_file:
            local_file.write(f.read())
    except HTTPError, e:
        print "HTTP Error:", e.code, url
    except URLError, e:
        print "URL Error:", e.reason, url
    try:
        file_handle = open(temp_file)
        data = file_handle.read()
        file_handle.close()
    except (OSError, IOError):
        print "Couldn't read %s" % temp_file
        return False
    try:
        os.unlink(temp_file)
        os.rmdir(os.path.dirname(temp_file))
    except (OSError, IOError):
        pass
    return data

def address_resolve(lat, lon):
    """Use Google's Reverse GeoCoding API to resolve coordinates to an street address."""
    try:
        url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s' % (lat, lon)
        data = download_file(url)
        obj = json.loads(data)
        return obj["results"][0]["formatted_address"]
    except:
        pass

def munkireport_prefs():
    """Read MunkiReport preferences file to term if we should lookup the
    client computers estimated address using Google's Reverse Geocode API."""
    munki_report = "/Library/Preferences/MunkiReport.plist"
    prefs = CFPreferencesCopyAppValue('ReportPrefs', munki_report)
    if prefs:
        if prefs.get('google_api_lookup'):
            return True
        else:
            return False
    else:
        return True

def main():
    """Locate Mac"""
    root_check()
    os_check()
    sysprefs_boxchk()
    add_python()
    lookup(5)
    global plist
    try:
        if munkireport_prefs() is True:
            if plist['Latitude']:
                add = dict(
                    Address=str(address_resolve(plist['Latitude'], plist['Longitude'])),
                )
                plist.update(add)
        else:
            add = dict(
                Address=str('Address lookup has been disabled on this computer.'),
            )
            plist.update(add)
    except:
        pass
    plistlib.writePlist(plist, location)

if __name__ == '__main__':
    main()
