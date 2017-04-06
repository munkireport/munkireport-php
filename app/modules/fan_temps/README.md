Fans and Temperatures Module
==============

Reports on fan speeds and temperatures from a client Mac.

The table provides the following information 'fan_temps':

* fan_0-8 (int) Current fan speed for fans 0 through 8
* fanmin0-8 (int) Minimum fan speed for fans 0 through 8
* fanmax0-8 (int) Maximum fan speed for fans 0 through 8
* fanlabel0-8 (string) Names of fans 0 through 8
* discin (string) Is an optical disc inserted, true/false
* \[four/five digit SMC sensor code] (float) The many known temperature sensors in Macs are listed in their own columns as floats and record the temperatures


Remarks
---

* Uses smckit to get data from SMC - [https://github.com/beltex/SMCKit/](https://github.com/beltex/SMCKit/)
* Because smckit is written in Swift, this modules required 10.9 or higher