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
import argparse
import logging
import json
from urllib2 import urlopen, URLError, HTTPError
from time import gmtime, strftime
import objc

# PyLint cannot properly find names inside Cocoa libraries, so issues bogus
# No name 'Foo' in module 'Bar' warnings. Disable them.
# pylint: disable=E0611
from CoreLocation import CLLocationManager, kCLDistanceFilterNone, kCLLocationAccuracyBest
from Foundation import NSRunLoop, NSDate, NSObject, NSBundle, CFPreferencesCopyAppValue
try:
    sys.path.append('/usr/local/munki/munkilib/')
    import FoundationPlist
except ImportError as error:
    logging.warn(error)
    exit(0)

# Skip manual check
if len(sys.argv) > 1:
    if sys.argv[1] == 'manualcheck':
        logging.info("Manual check: skipping")
        exit(0)

# Create cache dir if it does not exist
# pylint: disable=C0103
cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
if not os.path.exists(cachedir):
    os.makedirs(cachedir)

# Define location.plist
location = cachedir + "/location.plist"

# Create global plist object for data storage
plist = dict()

# Retrieve system UUID
IOKit_bundle = NSBundle.bundleWithIdentifier_('com.apple.framework.IOKit')

functions = [("IOServiceGetMatchingService", b"II@"),
             ("IOServiceMatching", b"@*"),
             ("IORegistryEntryCreateCFProperty", b"@I@@I"),
            ]

objc.loadBundleFunctions(IOKit_bundle, globals(), functions)
objc.loadBundle('CoreWLAN',
                bundle_path='/System/Library/Frameworks/CoreWLAN.framework',
                module_globals=globals())

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
        logging.warn("You must run this as root!")
        exit(0)

def os_vers():
    """Retrieve OS version."""
    maj_os_vers = platform.mac_ver()[0].split('.')[1]
    return maj_os_vers

def os_check():
    """Only tested on 10.8 - 10.11."""
    if not 8 <= int(os_vers()) <= 11:
        global plist
        status = "Your OS is not supported at this time: %s" % platform.mac_ver()[0]
        logging.warn(status)
        write_to_cache_location(None, status)
        exit(0)

def current_time_GMT():
    """Prints current date/time stamp"""
    currentTime = strftime("%Y-%m-%d %H:%M:%S +0000", gmtime())
    return currentTime

def write_to_cache_location(data, status):
    """Method to write plist data to disk"""
    global plist
    if data is not None:
        plist = data
    base_stats = dict(
        CurrentStatus=str(status),
        LastRun=str(current_time_GMT()),
        LS_Enabled=is_enabled,
    )
    plist.update(base_stats)
    plistlib.writePlist(plist, location)

def mac_has_wireless():
    """Check to see if this Mac has a wireless NIC. If it doesn't the
    CoreLocation lookup will fail."""
    wifi = CWInterface.interfaceNames()
    if wifi:
        return True
    else:
        return False

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
        logging.info("locationd preference directory not present")
        os.makedirs(perfdir)
    path_stub = "/private/var/db/locationd/Library/Preferences/ByHost/com.apple.locationd."
    das_plist = path_stub + uuid.strip() + ".plist"
    logging.info("Read current locationd settings")
    try:
        on_disk = FoundationPlist.readPlist(das_plist)
    except:
        p = {}
        FoundationPlist.writePlist(p, das_plist)
        logging.debug("creating empty locationd preferences")
        on_disk = FoundationPlist.readPlist(das_plist)
    val = on_disk.get('LocationServicesEnabled', None)
    if val != 1:
        logging.info("Location Services are not enabled")
        logging.debug("unload locationd service")
        service_handler('unload')
        on_disk['LocationServicesEnabled'] = 1
        FoundationPlist.writePlist(on_disk, das_plist)
        os.chown(das_plist, 205, 205)
        logging.debug("load locationd service")
        service_handler('load')
        logging.info("Location Services have been enabled")
    else:
        logging.info("Location Services are enabled")

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
    logging.debug("Current client.plist is: ")
    logging.debug(clients_dict)
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
        logging.info("we need to authorize python")
        logging.debug("unload locationd service")
        service_handler('unload')
        clients_dict[domain] = auth_plist
        FoundationPlist.writePlist(clients_dict, das_plist)
        os.chown(das_plist, 205, 205)
        logging.debug("load locationd service")
        service_handler('load')
        logging.debug("process a lookup so locationd service can properly authorize "
                      "python for your OS.")
        lookup(8)
        logging.debug("unload locationd service")
        service_handler('unload')
        clients_dict = FoundationPlist.readPlist(das_plist)
        auth_plist = clients_dict[domain]
        auth_plist["Authorized"] = True
        FoundationPlist.writePlist(clients_dict, das_plist)
        os.chown(das_plist, 205, 205)
        logging.debug("load locationd service")
        service_handler('load')
        # We need to wait 30 secs for locationd service to initial on first setup.
        status = "Location Services was enabled. Please wait 30 seconds before doing a lookup."
        write_to_cache_location(None, status)
        logging.warn(status)
        exit(0)
    else:
        logging.info("Python is enabled")

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

        logging.info("process a lookup request")
        global plist
        plist = dict(
            Latitude=str(lat),
            Longitude=str(lon),
            LatitudeAccuracy=int(verAcc),
            LongitudeAccuracy=int(horAcc),
            Altitude=int(altitude),
            GoogleMap=str(gmap),
            CurrentStatus="Successful",
        )
        logging.debug("output from successful lookup request: ")
        logging.debug(plist)
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
        )
        logging.info("lookup request failed")
        logging.debug("output from failed lookup request: ")
        logging.debug(plist)

def lookup(lookupTime):
    """Ask python to find current location."""
    finder = MyLocationManagerDelegate.alloc().init()
    NSRunLoop.currentRunLoop().runUntilDate_(NSDate.dateWithTimeIntervalSinceNow_(lookupTime))

def download_file(url):
    """Download a simple file from the Internet."""
    logging.info("download json address file from Google")
    try:
        temp_file = os.path.join(tempfile.mkdtemp(), 'tempdata')
        f = urlopen(url)
        with open(temp_file, "wb") as local_file:
            local_file.write(f.read())
        logging.info("download successful")
    except HTTPError, e:
        logging.debug("HTTP Error: %s, %s", e.code, url)
    except URLError, e:
        logging.debug("URL Error: %s, %s", e.reason, url)
    try:
        file_handle = open(temp_file)
        data = file_handle.read()
        file_handle.close()
    except (OSError, IOError):
        logging.debug("Couldn't read %s", temp_file)
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
        logging.info("google reverse lookup failed")
        pass

def munkireport_prefs():
    """Read MunkiReport preferences file to term if we should lookup the
    client computers estimated address using Google's Reverse Geocode API."""
    munki_report = "/Library/Preferences/MunkiReport.plist"
    prefs = CFPreferencesCopyAppValue('ReportPrefs', munki_report)
    if prefs:
        if prefs.get('location_address_lookup'):
            logging.info("address lookup enabled")
            return True
        else:
            logging.info("address lookup disabled")
            return False
    else:
        return True

def main():
    """Locate Mac"""
    parser = argparse.ArgumentParser(description="This script will \
            enable Location Services and Python if your mac is supported. \
            Python and the CoreLocation framework will process \
            our lookup request finding your mac if possible.")
    parser.add_argument('-v', '--verbose', action='count', default=0, help=" \
            More verbose output. May be specified multiple times.")
    args, unknown = parser.parse_known_args()

    levels = [logging.WARN, logging.INFO, logging.DEBUG]
    level = levels[min(len(levels)-1, args.verbose)]  # capped to number of levels

    logging.basicConfig(level=level,
                        format="%(levelname)s: %(message)s")

    os_check()
    if mac_has_wireless() is False:
        status = "No wireless interface found."
        write_to_cache_location(None, status)
        logging.warn(status)
        exit(0)
    root_check()
    sysprefs_boxchk()
    add_python()
    lookup(8)
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
    try:
        write_to_cache_location(plist, plist['CurrentStatus'])
        logging.info("Run status: %s", plist['CurrentStatus'])
    except KeyError:
        status = ("Error obtaining a location. LS was unresponsive "
                  "or a lookup timeout occurred.")
        logging.warn(status)
        write_to_cache_location(plist, status)

if __name__ == '__main__':
    main()
