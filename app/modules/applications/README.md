Applications Module
==============

Shows information about applications on the client.

Data can be viewed under the Applications tab on the client details page or using the Applications listing view 

Database:
* name - varchar(255) - name of the application
* path - TEXT - application's path
* last_modified - BIGINT - date application was last modified (epoch)
* obtained_from - varchar(255) - where application came from
* runtime_environment - varchar(255) - runtime environment of application
* version - varchar(255) - application's version
* info - TEXT - info about the application
* has64bit - int - 0/1 does application contain 64-bit code
* signed_by - varchar(255) - code signing of application
