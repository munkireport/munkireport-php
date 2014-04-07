Displays Information module
==============

Collects some relevant information from the output of `system_profiler -xml SPDisplaysDataType`

This is the table of values for 'displays':

* display_serial (string) Serial number of the display
* computer_serial (string) Serial number of the machine it's attached to
* vendor (string) Public name translated by the model from hex value
* model (string) Model reported
* manufactured (string) Aproximate date when it was manufactured
* native (string) Native resolution
* timestamp (int) UNIX timestamp

Remarks
---

* Only accounts for external displays.
