<?php

return [

/*
|===============================================
| Whitelist Management Console Access
|===============================================
|
| Whitelisting of IP addresses that can access the management interface 
|    (anything except for index.php?/report/ which is always allowed)
|  - You can provide either individual IP addresses (which will have /32 appended automatically)
|      or you can provide CIDR notation. See https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing for reference
|  - You can also provide a custom 403 page for traffic that does not have access to the management interface
|      Default: The default munkireport-php 403 client error page (no need to add this object if you 
|                 dont want the custom 403 page
|
*/

/*
| AUTH_NETWORK_WHITELIST_IP4="xxx.xxx.xxx.xxx, xxx.xxx.xxx.xxx"
| AUTH_NETWORK_REDIRECT_UNAUTHORIZED="http://fqdn/403.html"
| ]
*/
    'whitelist_ipv4' => env('AUTH_NETWORK_WHITELIST_IP4', []),
    'redirect_unauthorized' => env('AUTH_NETWORK_REDIRECT_UNAUTHORIZED', ''),
];
