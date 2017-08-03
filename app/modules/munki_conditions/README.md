Munki Conditions Module
=======================

Provides the results of [munki conditions](https://github.com/munki/munki/wiki/Conditional-Items), allowing admins to easily generate their own reportable datapoints without creating MunkiReport modules. Condition scripts are even easier to create using [munki-facts](https://github.com/munki/munki-facts).

Admin-Provided Condition scripts are found in `/usr/local/munki/conditions`.
The results of these scripts are stored in the 'conditions' dictionary in `/Library/Managed Installs/ManagedInstallReport.plist`

The following information is stored in the munki_conditions table:

For each entry in conditions:

* condition_key
* condition_value