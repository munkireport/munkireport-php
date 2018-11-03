<?php

class Network_model extends \Model
{
    
    public function __construct($serial_number = '')
    {
        parent::__construct('id', strtolower('network')); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial_number;
        $this->rs['service'] = ''; // Service name
        $this->rs['order'] = 0; // Service order
        $this->rs['status'] = 1; // Active = 1, Inactive = 0
        $this->rs['ethernet'] = ''; // Ethernet address
        $this->rs['clientid'] = ''; // Client id
        $this->rs['ipv4conf'] = ''; // IPv4 configuration (automatic, manual)
        $this->rs['ipv4ip'] = ''; // IPv4 address
        $this->rs['ipv4mask'] = ''; // IPv4 network mask as string
        $this->rs['ipv4router'] = '';  // IPv4 router address as string
        $this->rs['ipv6conf'] = ''; // IPv6 configuration (automatic, manual)
        $this->rs['ipv6ip'] = ''; // IPv6 address as string
        $this->rs['ipv6prefixlen'] = 0; // IPv6 prefix length as int
        $this->rs['ipv6router'] = '';  // IPv6 router address as string

        return $this;
    }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    public function process($data)
    {
        // Translate network strings to db fields
        $translate = array(
            'Ethernet Address: ' => 'ethernet',
            'Client ID: ' => 'clientid',
            'Wi-Fi ID: ' => 'ethernet',
            'IP address: ' => 'ipv4ip',
            'Subnet mask: ' => 'ipv4mask',
            'Router: ' => 'ipv4router',
            'IPv6: ' => 'ipv6conf',
            'IPv6 IP address: ' => 'ipv6ip',
            'IPv6 Prefix Length: ' => 'ipv6prefixlen',
            'IPv6 Router: ' => 'ipv6router');

        // ipv4 dhcp configuration strings
        // Unfortunately you cannot detect if IPv4 is off with
        // netwerksetup -getinfo
        $ipv4conf = array(
            'DHCP Configuration' => 'dhcp',
            'Manually Using DHCP Router Configuration' => 'manual',
            'BOOTP Configuration' => 'bootp',
            'Manual Configuration' => 'manual');
        
        $services = array();
        $order = 1; // Service order

        // Parse network data
        foreach (explode("\n", $data) as $line) {
            if (preg_match('/^Service: (.*)$/', $line, $result)) {
                $service = $result[1];
                $services[$service] = $this->rs; // Copy db fields
                $services[$service]['order'] = $order++;
                continue;
            }

            // Skip lines before service starts
            if (! isset($service)) {
                continue;
            }

            // Translate standard entries
            foreach ($translate as $search => $field) {
                if (strpos($line, $search) === 0) {
                    $services[$service][$field] = substr($line, strlen($search));
                    break;
                }
            }

            if (strpos($line, 'disabled')) {
                $services[$service]['status'] = 0;
                echo "$service disabled";
                continue;
            }

            // Look through the standard ipv4 config strings
            foreach ($ipv4conf as $search => $field) {
                if (strpos($line, $search) === 0) {
                    $services[$service]['ipv4conf'] = $field;
                    break;
                }
            }
        }

        //print_r($services);

        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Now only store entries with a valid ethernet address
        foreach ($services as $service => $data) {
            if ($data['ethernet'] && strlen($data['ethernet']) == 17) {
                $this->merge($data);
                $this->id = '';
                $this->service = $service;
                $this->save();
            }
        }
    }
}
