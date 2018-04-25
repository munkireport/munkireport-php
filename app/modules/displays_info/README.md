Displays Information module
==============

Collects some relevant information from the output of `system_profiler -xml SPDisplaysDataType`

This is the table of values for 'displays':

* type (bool) Wether the display is internal (built-in) or external
* display_serial (string) Serial number of the display
* serial_number (string) Serial number of the computer it's connected to
* vendor (string) Public name translated by the model from hex value
* model (string) Model reported
* manufactured (string) Approximate date when it was manufactured
* native (string) Native resolution
* timestamp (int) UNIX timestamp
* ui_resolution (string) Resolution of the user interface
* current_resolution (string) Current resolution
* color_depth (string) Color depth in use by the framebuffer
* display_type (string) Type of display; LCD/CRT/Projector
* main_display (boolean) Is main display
* mirror (boolean) Is Mirrored
* mirror_status (string) Mirroring status
* online (boolean) Display online
* interlaced (boolean) Interlacing in use
* rotation_supported (boolean) Supports rotation
* television (boolean) Is a television
* display_asleep (boolean) Is display asleep
* ambient_brightness (boolean) Ambient brightness set
* automatic_graphics_switching (boolean) Automatic graphics switching in use
* retina (boolean) Is Retina display
* edr_enabled (boolean) EDR enabled
* edr_limit (float) EDR limit
* edr_supported (boolean) Supports EDR
* connection_type (string) Type of display connection in use
* dp_dpcd_version (string) Version of DisplayPort
* dp_current_bandwidth (string) Current bandwidth of DisplayPort
* dp_current_lanes (int) Current number of lanes in use by DisplayPort
* dp_current_spread (string) Current DisplayPort spread
* dp_hdcp_capability (boolean) Supports HDCP
* dp_max_bandwidth (string) Maximum DisplayPort bandwidth
* dp_max_lanes (int) Maximum DisplayPort lanes
* dp_max_spread (string) Maximum DisplayPort spread
* virtual_device (boolean) Is virtual display device
* dynamic_range (string) Dynamic range currently in use
* dp_adapter_firmware_version (string) Firmware version of DisplayPort adapter

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
