Network module
==============

Provides network interface information gathered by `networksetup -getinfo`

The table provides the following information per 'networkservice':

* service (string) Service name
* status (int) Active = 1, Inactive = 0
* ethernet (string) Ethernet address
* clientid (string) Client id
* ipv4conf (string) IPv4 configuration (automatic, manual)
* ipv4ip (string) IPv4 address
* ipv4mask (string) IPv4 network mask
* ipv4router (string) IPv4 router address
* ipv6conf (string) IPv6 configuration (automatic, manual)
* ipv6ip (string) IPv6 address
* ipv6prefixlen (int) IPv6 prefix length
* ipv6router (string) IPv6 router address
* ipv4dns (string) IPv4 DNS server
* vlans (string) Set VLANs
* activemtu (interger) MTU in use by interface
* validmturange (string) Range of supported MTUs
* currentmedia (string) Current network media
* activemedia (string) Active network media
* searchdomain (string) Search domain

Remarks
---

* 'Wi-Fi ID' is stored as ethernet
* Entries without an ethernet address or valid IP address are not stored
* ipv4conf is undetermined when the ipv4 configuration is 'Off' in the user interface.