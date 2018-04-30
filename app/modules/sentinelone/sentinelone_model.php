<?php

use CFPropertyList\CFPropertyList;

class Sentinelone_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'sentinelone'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['active_threats_present'] = 0; //boolean
        $this->rs['agent_id'] = '';
        $this->rs['agent_install_time'] = '';
        $this->rs['agent_running'] = 0; //boolean
        $this->rs['agent_version'] = '';
        $this->rs['enforcing_security'] = 0; //boolean
        $this->rs['last_seen'] = '';
        $this->rs['mgmt_url'] = '';
        $this->rs['self_protection_enabled'] = 0; //boolean

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    
    // ------------------------------------------------------------------------

       public function process($data)
    {
		print $data;
		$parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);

		$plist = $parser->toArray();
		print_r ($plist);
		foreach (array('agent_id', 'agent_install_time', 'agent_version', 'mgmt_url', 'active_threats_present', 'agent_running', 'enforcing_security', 'self_protection_enabled', 'last_seen') as $item) {
			if (isset($plist[$item])) {
                if ($plist[$item] == true){
                    // If true boolean, set accordingly
                    $this->$item = '1';
                } else if ($plist[$item] == false){
                    // If false boolean, set accordingly
                    $this->$item = '0';		
                } else if ($plist[$item] == '') {
                	$this->$item = '0';
				} else {
					$this->$item = $plist[$item];
				}
			} else {
				$this->$item = '';
			}
		}
		var_dump($this->rs);			
		$this->save();
    } 

    public function get_active_threats_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN active_threats_present = '1' THEN 1 END) AS threats_present,
		COUNT(CASE WHEN active_threats_present = '0' THEN 1 END) AS threats_not_present
		FROM sentinelone
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_agent_running_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN agent_running = '1' THEN 1 END) AS running,
		COUNT(CASE WHEN agent_running = '0' THEN 1 END) AS not_running
		FROM sentinelone
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_self_protection_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN self_protection_enabled = '1' THEN 1 END) AS self_protected,
		COUNT(CASE WHEN self_protection_enabled = '0' THEN 1 END) AS not_self_protected
		FROM sentinelone
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_enforcing_security_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN enforcing_security = '1' THEN 1 END) AS enforced,
		COUNT(CASE WHEN enforcing_security = '0' THEN 1 END) AS not_enforced
		FROM sentinelone
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

	 public function get_versions()
	 {
		$out = array();
		$sql = "SELECT agent_version, COUNT(1) AS count
				FROM sentinelone
				GROUP BY agent_version
				ORDER BY COUNT DESC";
	
		foreach ($this->query($sql) as $obj) {
			if ("$obj->count" !== "0") {
				$obj->agent_version = $obj->agent_version ? $obj->agent_version : 'Unknown';
				$out[] = $obj;
			}
		}
		return $out;
     }
}

