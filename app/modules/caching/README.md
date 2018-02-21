Caching module
===============

Get status of OS X Server's machine service.


The results are stored in the table:

* id - Unique id
* serial_number - Serial Number
* entryIndex - Entry number of caching index
* collectionDate - Date when data was written
* expirationDate - Date when data will expire
* collectionDateepoch - Date when data was written UNIX time
* requestsfrompeers - Number of requests from peers
* requestsfromclients - Number of requests from clients
* bytespurgedyoungerthan1day - Number of bytes that were less than 24 hours old that have been deleted
* bytespurgedyoungerthan7days - Number of bytes that were less than 7 days old that have been deleted
* bytespurgedyoungerthan30days - Number of bytes that were less than 30 days old that have been deleted
* bytespurgedtotal - Total number of bytes that have been deleted from cache
* bytesfrompeerstoclients - Bytes sent to clients from peers
* bytesfromorigintopeers - Bytes from origin (Apple) to peers
* bytesfromorigintoclients - Bytes from origin to clients
* bytesfromcachetopeers - Bytes from cache to peers
* bytesfromcachetoclients - Bytes from cache to clients
* bytesdropped - Number of bytes dropped
* repliesfrompeerstoclients - Number of peers replied to clients
* repliesfromorigintopeers - Number of replies from origin to peers
* repliesfromorigintoclients - Number of replies from origin to clients
* repliesfromcachetopeers - Number of replies from cache to peers
* repliesfromcachetoclients - Number of replies from cache to clients
* bytesimportedbyxpc - Bytes imported by XPC
* bytesimportedbyhttp - Bytes imported over HTTPS
* importsbyxpc - Number of XPC imports
* importsbyhttp - Number of HTTPS imports