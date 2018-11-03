<?php
class Directory_service_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'directoryservice'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['which_directory_service'] = '';
        $this->rs['directory_service_comments'] = '';
        $this->rs['adforest'] = ''; // string
        $this->rs['addomain'] = ''; // string
        $this->rs['bound'] = ''; //string
        $this->rs['computeraccount'] = ''; // string
        $this->rs['createmobileaccount'] = 0;
        $this->rt['createmobileaccount'] = 'BOOL';  // Enabled = 1, Disabled = 0
        $this->rs['requireconfirmation'] = 0;
        $this->rt['requireconfirmation'] = 'BOOL';// Enabled = 1, Disabled = 0
        $this->rs['forcehomeinstartup'] = 0;
        $this->rt['forcehomeinstartup'] = 'BOOL'; // Enabled = 1, Disabled = 0
        $this->rs['mounthomeassharepoint'] = 0;
        $this->rt['mounthomeassharepoint'] = 'BOOL'; // Enabled = 1, Disabled = 0
        $this->rs['usewindowsuncpathforhome'] = 0;
        $this->rt['usewindowsuncpathforhome'] = 'BOOL'; // Enabled = 1, Disabled = 0
        $this->rs['networkprotocoltobeused'] = '';  // string smb or afp
        $this->rs['defaultusershell'] = '';     // string
        $this->rs['mappinguidtoattribute'] = ''; // string?
        $this->rs['mappingusergidtoattribute'] = ''; // string?
        $this->rs['mappinggroupgidtoattr'] = ''; // string?
        $this->rs['generatekerberosauth'] = 0;
        $this->rt['generatekerberosauth'] = 'BOOL'; // Enabled = 1, Disabled = 0
        $this->rs['preferreddomaincontroller'] = ''; // string?
        $this->rs['allowedadmingroups'] = ''; //array
        $this->rs['authenticationfromanydomain'] = 0;
        $this->rt['authenticationfromanydomain'] = 'BOOL'; // Enabled = 1, Disabled = 0
        $this->rs['packetsigning'] = ''; // allow = 1, ? = 0
        $this->rs['packetencryption'] = ''; // allow = 1, ? = 0
        $this->rs['passwordchangeinterval'] = ''; // int
        $this->rs['restrictdynamicdnsupdates'] = ''; // string?
        $this->rs['namespacemode'] = '';  // string?

        if ($serial) {
            $this->retrieve_record($serial);
            $this->serial = $serial;
        }
    }

    /**
     * Get bound stats
     *
     **/
    public function get_bound_stats()
    {
        $sql = "SELECT COUNT(1) as total,
						COUNT(CASE WHEN (which_directory_service LIKE 'Active Directory'
						OR which_directory_service LIKE 'LDAPv3') THEN 1 END) AS arebound
						FROM directoryservice
						LEFT JOIN reportdata USING(serial_number)
						".get_machine_group_filter();
        return(current($this->query($sql)));
    }
    
    /**
     * Get modified computer names
     *
     * Match computer_name with computeraccount in AD
     *
     * @param type var Description
     **/
    public function get_modified_computernames()
    {
        $machine = new Machine_model();
        $filter = get_machine_group_filter('AND');
        $trim = $this->trim('computeraccount', '$');
        $sql = "SELECT reportdata.serial_number, computeraccount, computer_name
				FROM directoryservice
				LEFT JOIN machine USING (serial_number)
				LEFT JOIN reportdata USING (serial_number)
				WHERE NOT directoryservice.computeraccount = ''
				AND LOWER($trim) != LOWER(computer_name)
				".$filter;
        return $this->query($sql);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author gmarnin
     **/
    public function process($data)
    {
        
        // process copied from network model. Translate strings to db fields. needed? . error proof?
            $translate = array('Directory Service = ' => 'which_directory_service',
                                'Active Directory Comments = ' => 'directory_service_comments',
                                'Active Directory Forest = ' => 'adforest',
                                'Active Directory Domain = ' => 'addomain',
                                'Bound = ' => 'bound',
                                'Computer Account = ' => 'computeraccount',
                                'Create mobile account at login = ' => 'createmobileaccount',
                                'Require confirmation = ' => 'requireconfirmation',
                                'Force home to startup disk = ' => 'forcehomeinstartup',
                                'Mount home as sharepoint = ' => 'mounthomeassharepoint',
                                'Use Windows UNC path for home = ' => 'usewindowsuncpathforhome',
                                'Network protocol to be used = ' => 'networkprotocoltobeused',
                                'Default user Shell = ' => 'defaultusershell',
                                'Mapping UID to attribute = ' => 'mappinguidtoattribute',
                                'Mapping user GID to attribute = ' => 'mappingusergidtoattribute',
                                'Mapping group GID to attribute = ' => 'mappinggroupgidtoattr',
                                'Generate Kerberos authority = ' => 'generatekerberosauth',
                                'Preferred Domain controller = ' => 'preferreddomaincontroller',
                                'Allowed admin groups = ' => 'allowedadmingroups',  // ARRAY alert
                                'Authentication from any domain = ' => 'authenticationfromanydomain',
                                'Packet signing = ' => 'packetsigning',
                                'Packet encryption = ' => 'packetencryption',
                                'Password change interval = ' => 'passwordchangeinterval',
                                'Restrict Dynamic DNS updates = ' => 'restrictdynamicdnsupdates',
                                'Namespace mode = ' => 'namespacemode');

        //clear any previous data we had
        foreach ($translate as $search => $field) {
            if (array_key_exists($field, $this->rt) && $this->rt[$field] == 'BOOL') {
                $this->$field = 0;
            } else {
                $this->$field = '';
            }
        }

        // Parse data
        foreach (explode("\n", $data) as $line) {
            // Translate standard entries
            foreach ($translate as $search => $field) {
                if (strpos($line, $search) === 0) {
                    $value = substr($line, strlen($search));
                    
                    // use bool when possible
                    if (strpos($value, 'Enabled') === 0) {
                        $this->$field = 1;
                        break;
                    } elseif (strpos($value, 'Disabled') === 0) {
                        $this->$field = 0;
                        break;
                    }
                    
                    $this->$field = $value;
                    break;
                }
            }
        } //end foreach explode lines
            $this->save();
    }
}
