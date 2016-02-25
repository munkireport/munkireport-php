<?php
class munkiinfo_model extends Model {

        function __construct($serial='')
        {
                parent::__construct('id', 'munkiinfo'); //primary key, tablename
                $this->rs['id'] = '';
                $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
                $this->rs['additionalhttpheaders'] = ''; //Array, probably needs more work
                $this->rs['applesoftwareupdatesonly'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['catalogurl'] = '';
                $this->rs['clientcertificatepath'] = '';
                $this->rs['clientidentifier'] = '';
                $this->rs['clientkeypath'] = '';
                $this->rs['clientresourcesfilename'] = '';
                $this->rs['clientresourceurl'] = '';
                $this->rs['daysbetweennotifications'] = ''; // integer
                $this->rs['followhttpredirects'] = '';
                $this->rs['helpurl'] = '';
                $this->rs['iconurl'] = '';
                $this->rs['installapplesoftwareupdates']  = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['installrequireslogout'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['localonlymanifest'] = '';
                $this->rs['logfile'] = '';
                $this->rs['logginglevel'] = ''; // integer
                $this->rs['logtosyslog'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['managedinstalldir'] = '';
                $this->rs['manifesturl'] = '';
                $this->rs['msudebuglogenabled'] = '';
                $this->rs['msudebuglogenabled'] = '';
                $this->rs['msulogenabled'] = '';
                $this->rs['munkiprotocol'] = '';
                $this->rs['packageurl'] = '';
                $this->rs['packageverificationmode'] = '';
                $this->rs['showremovaldetail'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['softwarerepocacertificate'] = '';
                $this->rs['softwarerepocapath'] = '';
                $this->rs['softwarerepourl'] = '';
                $this->rs['softwareupdateserverurl'] = '';
                $this->rs['suppressautoinstall'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['suppressloginwindowinstall'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['suppressstopbuttononinstall'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['suppressusernotification'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['unattendedappleupdates'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['useclientcertificate'] = 'BOOL';  // Enabled = 1, Disabled = 0
                $this->rs['useclientcertificatecnasclientidentifier'] = '';
                // Schema version, increment when creating a db migration
                $this->schema_version = 0;
                
                // Add indexes
                $this->idx[] = array('serial_number');

                // Create table if it does not exist
                $this->create_table();
                		
                if ($serial) {
                	$this->retrieve_record($serial);
                	
                $this->serial = $serial;
		}

        }

	/**
	 * Get protocol statistics
	 *
	 *
	 **/
	public function get_protocol_stats()
	{
		$sql = "SELECT  COUNT(1) as total,
						COUNT(CASE WHEN munkiprotocol = 'http' THEN 1 END) AS http,
						COUNT(CASE WHEN munkiprotocol = 'https' THEN 1 END) AS https,
						COUNT(CASE WHEN `munkiprotocol` = 'localrepo' THEN 1 END) AS localrepo
			 			FROM munkiinfo
			 			LEFT JOIN reportdata USING (serial_number)
			 			".get_machine_group_filter();
		return current($this->query($sql));

	}     

        // ------------------------------------------------------------------------

        /**
         * Process data sent by postflight
         *
         * @param string data
         * @author erikng
         **/
        function process($plist)
        {       
        
        		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        		$parser = new CFPropertyList();
        		$parser->parse($plist, CFPropertyList::FORMAT_XML);
        		$plist = $parser->toArray();
				
				if( ! $plist)
				{
					throw new Exception("No info in report", 1);
				}
				
				// Delete previous set
				$this->delete_where('serial_number=?', $this->serial_number);
				
				// Copy default values
				$empty = $this->rs; 
				
				// Loop through returned values
				foreach($plist AS $info)
				{
        			// Reset values
					$this->rs = $empty;
					
					$this->merge($info);

            		$this->id = '';
            		$this->create();				
            	}
				
        }
}
