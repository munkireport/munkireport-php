<?php

// Utility class to render plist files

namespace munkireport\lib;

class IPBlocker {

    private $config;

    private function __construct($config) {
        $this->config = $config;
    }
    
    protected function parse_range($ip, $range) {
        if (strpos($range, '/')) return cidr_match($ip, $range);
        $range = $range . '/32';
        return cidr_match($ip, $range);
    }
    
    protected function cidr_match($ip, $range) {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }

    public function check_ip($remote_address) {
        // if user is going to the report uri, allow the connection regardless
        if (substr(($GLOBALS[ 'engine' ]->get_uri_string()), 0, 8) === "report/") { return 1; }
      
        // for loop through the configuration setting to check if any IP addresses match - if so 
        //     allow traffi$
        foreach (conf('manage_ipwhitelist') as $range) {
            if (parse_range($remote_address, $range)) { return 1; }
        }
      
        // if a custom 403 page is defined, send traffic to that page
        if (!empty(conf('403_unauth'))) {
            header(("Location: " . conf('403_unauth')), true, 301);
            exit();
        }
      
        // otherwise send it to the local servers 403 page
        header(("Location: " . (conf('webhost')."/403.html")), true, 301);
        exit();
    }
}
