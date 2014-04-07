Displays Information module
==============

Collects some relevant information from the output of `system_profiler -xml SPDisplaysDataType`

This is the table of values for 'displays':

* monitor_serial (string) unique? n/a? #todo
* computer_serial (string) Serial number of the machine it's attached to
* vendor (string) Public name translated by the model from hex value
* model (string) Model reported
* manufactured (string) Aprox. date when it was manufactured
* native (string) Native resolution
* type (bool) Internal = 0, External = 1
* timestamp (int) UNIX timestamp

Remarks
---

* iMacs won't report their built-in display (even when an external is present? todo)
