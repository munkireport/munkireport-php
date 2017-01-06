# App Usage module
Idea and collaboration by @gmarnin

===============

Reports app usage and when app was last opened and closed


The results are stored in the table:

* id - Unique id
* serial_number - Serial Number
* event - Data of if app launched or quit
* bundle_id - Bundle ID app app event
* app_version - App version of app event
* app_path - Path of App of app event
* last_time_epoch - UNIX time of app event
* last_time - Human readable last time of app event
* number_time - How many times the app was launched or quitted

#Dependencies
Uses [crankd] (https://github.com/MacSysadmin/pymacadmin) and [ApplicationUsage.py] (https://github.com/google/macops/tree/master/crankd) for application usage gathering. Will automatically install all required files if they are not already installed. 
