Location module
==============

Provides location information on where a Mac is physical located.

The script uses Apple's CoreLocation framework to determine the approximate latitude, and longitude of a Mac.

Author: Clayton Burlison - https://clburlison.com  
Created: Jan. 25, 2016  
Updated: Feb. 14, 2016  


##Limitations
This module is limited to 10.8 - 10.11. On each run Location Services will be enabled, and the system Python binary will be given access to Location Services (LS). Due to how LS and the CoreLocation framework interact the first run to enable all the services has an exit after enabling Python plus LS. This exit will normally not be seen as Munki will automatically deploy the script and provide lookups on the second automatic munki run. The reason for this "delay" in activation is because the services need about 30 seconds to initialize the locationd daemon. However, munki's preflight script will timeout the script for waiting too long. After initial setup, sequential runs take about 6-8 seconds and have a pretty high accuracy.

###Google Geocoding API
We are using Google's Geocoding API for reverse address lookup. This API comes with the following limits: 

* 2,500 free requests per day
* 10 requests per second

Additional information can be found: https://developers.google.com/maps/documentation/geocoding/usage-limits

###CoreLocation
CoreLocation requires a wireless adapter to obtain lookup information. On older Macs that did not ship with a wireless adapter this script will exit without enabling Location Services or authorizing Python for lookups. If your Mac has the wireless card disabled there is a chance that this script will still obtain a location based off of CoreLocation's cached data. Your Mac does not need to be connected to an active SSID for location data to be found just enabled.

Of interest:
> Your approximate location is determined using information from local Wi-Fi networks, and is collected by Location Services in a manner that doesn’t personally identify you.
>
> -- https://support.apple.com/en-us/HT204690

##Settings

The call out to Google's API can be disabled if you wish. If you disable this api call the only data provided to MunkiReport will be client side data from CoreLocation: Latitude, Longitude, Accuracy, and Altitude.

Disable Google API loopups:

```bash
sudo defaults write /Library/Preferences/MunkiReport ReportPrefs -dict-add location_address_lookup -bool False
````

Enable Google API lookups (default):
```bash
sudo defaults write /Library/Preferences/MunkiReport ReportPrefs -dict-add location_address_lookup -bool True
```

This can also be disabled with a profile. Example: [@clburlison/profiles](https://github.com/clburlison/profiles/blob/master/clburlison/MunkiReportDisableAddressLookups.mobileconfig).

##Errors 

| Error message  |  Meaning |
|---|---|
| Your OS is not supported at this time  | Your OS is either too old or possibly too new for this script. |
| Location Services was enabled. Please wait 30 seconds before doing a lookup | This status message should only be seen on the first run of this script. We need to wait 30 seconds before doing a lookup. |
| No wireless interface found | Without a wireless interface we cannot find geodata. |
| Unsuccessful: Unable to locate  | CoreLocation was unable to locate your Mac. Apple Error Code: 3 |
| Unsuccessful: Denied  | CoreLocation was denied access to location service. Apple Error Code: 2  |
| Unsuccessful: Restricted  | CoreLocation obtained restricted access to location services. Apple Error Code: 1  |
| Unsuccessful: Not Determined | CoreLocation could not determine a geolocation. Apple Error Code: 0 |
| Unsuccessful: Location Services Disabled | Location Services is disabled. This is often due to your AirPort card being turned off. | 
| Error obtaining a location. LS was unresponsive or a lookup timeout occurred. | General Error. Location services failed to find a location for one or more reasons. |

##Testing
In most cases the warnings produced by this script in a normal run will provide enough information to determine what the issue is. However, if you are unsure why a lookup is failing the best way to get more information is with the `verbose` output option.

To see warnings and information use:
```bash
sudo /usr/local/munki/preflight.d/location.py -v
```

To see a full output of warnings, information, and debug statements use:
```bash
sudo /usr/local/munki/preflight.d/location.py -vv
```

##Database entries

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

#Credits
Based off of works by:  
@arubdesu - https://gist.github.com/arubdesu/b72585771a9f606ad800  
@pudquick - https://gist.github.com/pudquick/c7dd1262bd81a32663f0  
@pudquick - https://gist.github.com/pudquick/329142c1740500bd3797  
@lindes   - https://github.com/lindes/get-location/  
University of Utah, Marriott Library - https://github.com/univ-of-utah-marriott-library-apple/privacy_services_manager  