Location module
==============

Provides location information on where a Mac is physical located.

This module relies on `pinpoint` an external project located here: https://github.com/clburlison/pinpoint

Author: Clayton Burlison - https://clburlison.com  

#Database entries

The following data is collected and can be accessed via MunkiReport's API:

* Address - Str, Estimated street address
* Latitude - Str, Latitude
* Longitude - Str, Longitude
* LatitudeAccuracy - Int, Latitude Accuracy
* LongitudeAccuracy - Int, Longitude Accuracy
* Altitude - Int, Altitude
* GoogleMap - Str, Pre-populated Google Maps URL
* LastRun - Str, Last run time stored in UTC time
* LastLocationRun - Str, Last time a location run was done successfully
* CurrentStatus - Str, Friendly message describing last run
* LS_Enabled - Bool, are Location Services enabled
* StaleLocation - Str, Yes/No to indicate if the lookup location is from an old run