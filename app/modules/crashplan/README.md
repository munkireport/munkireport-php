Crashplan module
================

Reports on Crashplan (Pro/Home/Enterprise) backup information 

Retrieves information from /Library/Logs/CrashPlan/history.log.0.

The following information is stored in the table:

* id - Unique ID
* destination - Name of destination
* last_success - Timestamp of last successful backup
* last_failure - Timestamp of last failure
* reason - Reason of last failure
* duration - Duration in seconds

