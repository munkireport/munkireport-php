Location module
==============

Provides location information on where a Mac is physical located.

The script uses Apple's CoreLocation framework to determine the approximate latitude, and longitude of a Mac.

Author: Clayton Burlison <https://clburlison.com>  
Created: Jan. 25, 2016  

Based off of works by:  
@arubdesu - https://gist.github.com/arubdesu/b72585771a9f606ad800  
@pudquick - https://gist.github.com/pudquick/c7dd1262bd81a32663f0  
@pudquick - https://gist.github.com/pudquick/329142c1740500bd3797  
@lindes   - https://github.com/lindes/get-location/  
University of Utah, Marriott Library - https://github.com/univ-of-utah-marriott-library-apple/privacy_services_manager  


Limitations
==============
This module is limited to 10.8 - 10.11. On each run Location Services will be enabled, and the system Python binary will be given access to Location Services (LS). Due to how LS and the CoreLocation framework interact the first run to enable all the services has an exit after enabling Python plus LS. This exit will normally not be seen as Munki will automatically deploy the script and provide lookups on the second automatic munki run. The reason for this "delay" in activation is because the services need about 30 seconds to initialize the locationd daemon. However, munki's preflight script will timeout the script for waiting too long. After initial setup, sequential runs take about 6-8 seconds and have a pretty high accuracy.

We are using Google's Geocoding API for reverse address lookup. This API comes with the following limits: 

* 2,500 free requests per day
* 10 requests per second

Additional information can be found: https://developers.google.com/maps/documentation/geocoding/usage-limits

Settings
==============
The call out to Google's API can be disabled if you wish. If you disable this api call the only data provided to MunkiReport will be client side data from CoreLocation: Latitude, Longitude, Accuracy, and Altitude.

Disable Google API loopups:

```bash
sudo defaults write /Library/Preferences/MunkiReport ReportPrefs -dict-add google_api_lookup -bool False
````

Re-enable Google API lookups (default):
```bash
sudo defaults write /Library/Preferences/MunkiReport ReportPrefs -dict-add google_api_lookup -bool True
```

This can also be disabled with a profile. Example: [@clburlison/profiles](https://github.com/clburlison/profiles/blob/master/MunkiReportDisableAddressLookups.mobileconfig).


Notes
==============

The following data is created by this script and can be accessed via MunkiReports API:

* Address - Str, Estimated street address
* Latitude - Str, Latitude
* Longitude - Str, Longitude
* LatitudeAccuracy - Int, Latitude Accuracy
* LongitudeAccuracy - Int, Longitude Accuracy
* Altitude - Int, Altitude
* GoogleMap - Str, Pre-populated Google Maps URL
* LastRun - Str, Last run time stored in UTC time
* CurrentStatus - Str, Friendly message describing last run
* LS_Enabled - Bool, are Location Services enabled.