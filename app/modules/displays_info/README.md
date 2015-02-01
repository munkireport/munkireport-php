Displays Information module
==============

Collects some relevant information from the output of `system_profiler -xml SPDisplaysDataType`

This is the table of values for 'displays':

* type (bool) Wether the display is internal (built-in) or external
* display_serial (string) Serial number of the display
* serial_number (string) Serial number of the computer it's connected to
* vendor (string) Public name translated by the model from hex value
* model (string) Model reported
* manufactured (string) Aproximate date when it was manufactured
* native (string) Native resolution
* timestamp (int) UNIX timestamp

Remarks
---

The default configuration of the module is to delete any previous display information it had for the computer. Also be advised that `system_profiler` does not return any display information when the client is at the login window.

Nonetheless you can configure it to keep old data by adding this to your config.php `$conf['keep_previous_displays'] = TRUE;`. Some examples of what to expect in each case:

### Example of default behaviour:

* Scenario 1:
  * Laptop1 reports in the morning about the built-in display and an external display --> mr-php stores and displays both

  * Laptop1 reports later in the day about only the built-in display --> mr-php deletes any info display info it had for the laptop and stores the new data
* Scenario 2:
  * Laptop1 reports in the morning about the built-in display and an external display --> mr-php stores and displays both

  * Laptop1 is turned off and stored in a shelve.

  * Computer2 reports it's built-in display and the same external display --> mr-php stores both. At this point mr-php displays four entries, including a duplicate one for the display.

### Example of preserving historic data:

* Scenario 1:
  * Laptop1 reports in the morning about the built-in display and an external display --> mr-php stores and displays both.

  * Laptop1 reports later in the day about only the built-in display --> mr-php overwrites the info it had for the built-in display but keeps the external display info. Both are displayed with the date when they were last seen.
* Scenario 2:
  * Laptop1 reports in the morning about the built-in display and an external display --> mr-php stores and displays both.

  * Laptop1 is turned off and stored in a shelve.

  * Computer2 reports it's built-in display and the same external display --> mr-php both under computer2's s/n overwriting the external display info it had. Computer2 becomes the last-seen-in computer. Three entries are displayed, possibly matching the number of company assets.

* Scenario 3:

  * Laptop1 reports in the morning about the built-in display and an external display --> mr-php stores and displays both
  * The display is broken/stolen/etc.
  * The display remains associated with Laptop1 until you remove Laptop1 from MR
