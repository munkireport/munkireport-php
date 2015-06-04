Location module
==============

Provides location information on where the Mac is physical located.

The script uses wifi and the Google map api to determine the approximate latitude, longitude and address of the Mac.

Credit for script goes to bollman https://jamfnation.jamfsoftware.com/discussion.html?id=12300


Limitations
==============

Good question. There maybe a limit of 2,500 requests per 24 hour period and 5 requests per second but I'm not sure.


Notes
==============

The Google api also reports back how accurate the location is. 
The accuracy of the estimated location is in meters. This represents the radius of a circle around the given location.
https://developers.google.com/maps/documentation/business/geolocation/