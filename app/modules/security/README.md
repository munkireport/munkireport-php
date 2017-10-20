Security module
================


The following information is stored in the security table:


* Gatekeeper Status 10.7+
* SIP Status 10.11+
* Firmware Password State
* Application Firewall State

In the future, this module can be expanded to support xprotect, screen saver password, etc...

For the application firewall state, there are three possible values:
* Disabled = 0 - the firewall is fully disabled
* Enabled = 1 - the firewall is enabled.
* Block All = 2 - the firewall is enabled, and "Block all incoming connections" is checked in the GUI
