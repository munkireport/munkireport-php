<?php
namespace munkireport\lib;

class AuthWhitelist {
  
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    private function parse_range($ip, $range) {
        if (strpos($range, '/')) return $this->cidr_match($ip, $range);
        $range = $range . '/32';
        return $this->cidr_match($ip, $range);
    }

    private function cidr_match($ip, $range) {
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
        //     allow traffic

        foreach ($this->config['whitelist_ipv4'] as $range) {
            if ($this->parse_range($remote_address, $range)) { return 1; }
        }

        // if a custom 403 page is defined, send traffic to that page
        if (isset($this->config['redirect_unauthorized']) && ! empty($this->config['redirect_unauthorized'])) {
            header(("Location: " . ($this->config['redirect_unauthorized'])), true, 301);
            exit();
        }

        // otherwise send it to the local servers 403 page
        redirect('error/client_error/403');
    }
}
