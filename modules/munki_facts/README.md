Munki Conditions Module
=======================

Provides the results of [munki conditions](https://github.com/munki/munki/wiki/Conditional-Items), allowing admins to easily generate their own reportable datapoints without creating custom MunkiReport modules. Condition scripts are even easier to create using [munki-facts](https://github.com/munki/munki-facts).

Admin-Provided Condition scripts are found in `/usr/local/munki/conditions`.
The results of these scripts are stored in the 'Conditions' dictionary in `/Library/Managed Installs/ManagedInstallReport.plist`.

Both keys and values are truncated to 65,535 characters.

The following information is stored in the munki_facts table:

* serial_number
* condition_key
* condition_value

This module is mostly based on work by clburlison, erikng, bochoven and more.
