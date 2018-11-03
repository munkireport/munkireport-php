WiFi module
==============

Provides connected WiFi network information gathered by `/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport -I`

The table provides the following information:

* agrctlrssi (null int) Aggregate RSSI (decibels) 
* agrextrssi (null int) Aggregate external RSSI (decibels)
* agrctlnoise (null int) Aggregate noise (decibels)
* agrextnoise (null int) Aggregate external noise (decibles)
* state (string) WiFi state (running, init, off)
* op_mode (string) Access point mode
* lasttxrate (null int) Last transmit rate (Mbps)
* lastassocstatus (string) Last association status
* maxrate (null int) Maximum supported transmit rate (Mbps)
* x802_11_auth (string) Type of authentication
* link_auth (string) Link authentication type
* bssid (string) Access point's BSSID
* ssid (string) SSID or name of the connected wireless network
* mcs (string) Modulation and Coding Scheme
* channel (string) Channel of wireless network

Remarks
---

* 'init' state indicates that WiFi is on, but not connected to any networks
