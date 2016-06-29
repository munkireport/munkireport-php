DeployStudio module
==============

Provides warranty status information by querying the Apple Support Web page

The table provides the following information:

* purchase_date (string) Date in the following format: yyyy-mm-dd
* end_date (string) Date in the following format: yyyy-mm-dd
* status (string) One of the following strings: 
  * Unregistered serialnumber
  * No Applecare
  * Supported
  * No information found
  * Virtual Machine
  * Expired

Remarks
---

* The client triggers the server to do a lookup once a day
* Warranty status is **not** checked when the status is 'Expired', Virtual Machine'
* Warranty status is **not** checked when the status is 'Supported' and end_date > today
* The status 'Virtual Machine' is for VMware only at the moment (detects upper and lowercase in serial number)
