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

This module is limited to 10.8 - 10.11. On each run Location Services will be enabled, and the system Python binary will be given access to Location Services. Due to how Location Services and the CoreLocation framework interact the first run to enable all the services takes about 50 seconds to run while sequential runs take about 10 seconds.

We are using Google's Geocoding API for reverse address lookup. This API comes with the following limits: https://developers.google.com/maps/documentation/geocoding/usage-limits

* 2,500 free requests per day
* 10 requests per second

Notes
==============

The following data is created by this script:

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