Extensions module
==============

Provides information about third party extensions (kexts).

Database:
* bundle_id - varchar(255) - Bundle ID of the extension
* version - varchar(255) - Version of the extension
* path - TEXT - Directory of extension
* developer - varchar(255) - Codesigner's developer name
* teamid - varchar(255) - Developer's TeamID (useful for System Extension Policy whitelisting in MDM)
* executable - TEXT - Location of executable within extension

Special thanks yo frogor for helping with the data gathering script.
