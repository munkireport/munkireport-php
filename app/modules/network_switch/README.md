Network Switch module
==============

This module uses Cisco Discovery Protocol (CDP) to physically locate where the Mac is connected to on the network

If you don't use Cisco switches, you can opt out of this module using the Modules section of the config.php file.
Pull requests for other switch manufactures is encouraged


The data collection script is set to run every 7 days.
You set how often the script should run (in seconds) by changing the runtime variable on line 18 of /network_switch/scripts/ciscoswitch 


The following data is collected and displayed under the Network tab on the client details page or using the Network report list view:

* The date the switch was last queried 
* The switch port number the Mac is plugged into
* Which vlan the Mac is on
* The physical location on the switch (if it is set on the switch)
* IP address of the switch

It can take the script up to 30 seconds to run depending on you network configuration. Still beats using a Fluke in the field



The script checks to find the active ethernet interface on the Mac. It then runs:

`/usr/sbin/tcpdump -nn -vvv -i $mainInt_excludingwifi -s 1500 -c 1 'ether[20:2] == 0x2000'`

Options explained:

* -nn don't do dns or port number lookups

* -vvv very verbose output

* -i $mainInt_excludingwifi specifies the active ethernet interface to use

* -s 1500 capture 1500 bytes of the packet (typical MTU size)

* -c 1 capture one packet and exit

* ether[20:2] == 0x2000 capture only packets that have a 2 byte value of hex 2000 starting at byte 20


