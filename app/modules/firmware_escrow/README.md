# Firmware Escrow module

Escrow a random Firmware password.

A sample script named `firmware-set.sh` is provided to generate a random 6 character password and set that password using
the `firmwarepasswd` binary. If you plan to use the sample script, please edit line 51 `"${MacIP}" != "172."` to an ip range based on your environment.
Note that `firmwarepasswd` is only available on macOS 10.10 and newer.


The module provides the following information in the client details and listings view:

+ Enabled Date - The data the Firmware password was enabled
+ Firmware Password - The random password
+ Firmware Mode - Records the current mode setting


# To do

+ Add a section to the Firmware script to read the database and confirm the password has been recorded before the Mac is rebooted.
Prefer if the password to the db wasn't in the script.
