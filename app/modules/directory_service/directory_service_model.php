<?php
class Directory_service_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'directoryservice'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['which_directory_service'] = '';
		$this->rs['directory_service_comments'] = '';   
		$this->rs['adforest'] = ''; // string
		$this->rs['addomain'] = ''; // string
		$this->rs['computeraccount'] = ''; // string
		$this->rs['createmobileaccount'] = 0; $this->rt['createmobileaccount'] = 'BOOL';  // Enabled = 1, Disabled = 0
		$this->rs['requireconfirmation'] = 0; $this->rt['requireconfirmation'] = 'BOOL';// Enabled = 1, Disabled = 0
		$this->rs['forcehomeinstartup'] = 0; $this->rt['forcehomeinstartup'] = 'BOOL'; // Enabled = 1, Disabled = 0
		$this->rs['mounthomeassharepoint'] = 0; $this->rt['mounthomeassharepoint'] = 'BOOL'; // Enabled = 1, Disabled = 0
		$this->rs['usewindowsuncpathforhome'] = 0; $this->rt['usewindowsuncpathforhome'] = 'BOOL'; // Enabled = 1, Disabled = 0
		$this->rs['networkprotocoltobeused'] = '';	// string smb or afp	
		$this->rs['defaultusershell'] = '';	// string
		$this->rs['mappinguidtoattribute'] = ''; // string?
		$this->rs['mappingusergidtoattribute'] = ''; // string?
		$this->rs['mappinggroupgidtoattr'] = ''; // string?
		$this->rs['generatekerberosauth'] = 0; $this->rt['generatekerberosauth'] = 'BOOL'; // Enabled = 1, Disabled = 0
		$this->rs['preferreddomaincontroller'] = ''; // string?
		$this->rs['allowedadmingroups'] = ''; //array
		$this->rs['authenticationfromanydomain'] = 0; $this->rt['authenticationfromanydomain'] = 'BOOL'; // Enabled = 1, Disabled = 0
		$this->rs['packetsigning'] = ''; // allow = 1, ? = 0
		$this->rs['packetencryption'] = ''; // allow = 1, ? = 0
		$this->rs['passwordchangeinterval'] = ''; // int
		$this->rs['restrictdynamicdnsupdates'] = ''; // string?
		$this->rs['namespacemode'] = '';  // string?

		$this->idx[] = array('which_directory_service');
		$this->idx[] = array('directory_service_comments');			
		$this->idx[] = array('allowedadmingroups');
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 2;
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial) {
			$this->retrieve_one('serial_number=?', $serial);
			$this->serial = $serial;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author gmarnin
	 **/
	function process($data)
	{
		
		// process copied from network model. Translate strings to db fields. needed? . error proof?
        	$translate = array('Directory Service = ' => 'which_directory_service',
								'Active Directory Comments = ' => 'directory_service_comments',
								'Active Directory Forest = ' => 'adforest',
								'Active Directory Domain = ' => 'addomain',
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
		foreach($translate as $search => $field)
		{
			if(array_key_exists($field, $this->rt) && $this->rt[$field] == 'BOOL')
			{
				$this->$field = 0;
			}
			else
			{
				$this->$field = '';
			}
		}

		// Parse data
		foreach(explode("\n", $data) as $line) {
		    // Translate standard entries
			foreach($translate as $search => $field) {
			    
			    if(strpos($line, $search) === 0) {
				    
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