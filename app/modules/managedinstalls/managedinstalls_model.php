<?php
class managedinstalls_model extends Model {
        
	function __construct($serial='')
	{
		parent::__construct('id', 'managedinstalls'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
        $this->rs['name'] = '';
        $this->rs['display_name'] = '';
        $this->rs['version'] = '';
        $this->rs['size'] = 0;
        $this->rs['installed'] = 0; // 1 = installed, 0 = not installed
        $this->rs['status'] = ''; // installed, pending, failed
        $this->rs['type'] = ''; // munki, applesus
        
        // Add indexes
        $this->idx[] = array('serial_number');
        $this->idx[] = array('name');
        $this->idx[] = array('version');
        $this->idx[] = array('name', 'version');
        $this->idx[] = array('display_name');
        $this->idx[] = array('status');
        $this->idx[] = array('type');

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
		// Create table if it does not exist
		$this->create_table();
		
        if ($serial)
		{
		    $this->retrieve_record($serial);
            if ( ! $this->rs['serial_number'])
            {
                $this->serial = $serial;
            }
		}
			
	}
	
    // ------------------------------------------------------------------------
    
    /**
     * Setter for serial_number
     */
    public function setSerialNumber($serial_number)
    {
        $this->serial_number = $serial_number;
    }
    
    // ------------------------------------------------------------------------
	
	/**
	 * Process data sent by postflight
	 *
	 * @param string data property list
	 * 
	 **/
	function process($data)
	{		
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();
        if( ! $mylist)
        {
            throw new Exception("No Data in report", 1);
        }
                
        // Run processData
        $this->processData($mylist);
    }
    
    /**
     * Process Data
     *
     * Process data provided
     *
     * @param array $mylist array with entries
     */
    public function processData($mylist)
    {
            
        // Remove previous data
        $this->delete_where('serial_number=?', $this->serial_number);

        // List with fillable entries
        $fillable = array(
            'name' => '',
            'display_name' => '',
            'version' => '',
            'size' => 0,
            'installed' => 0,
            'status' => '',
            'type' => '',
        );
        
        # Loop through list
        foreach($mylist as $name => $props){
            
            // Get an instance of the fillable array
            $temp = $fillable;
            
            // Add name to temp
            $temp['name'] = $name;
            
            // Copy values and correct type
            foreach ($temp as $key => $value) {
                if(array_key_exists($key, $props)){
                    $temp[$key] = $props[$key];
                    settype($temp[$key], gettype($value));
                }
            }
            
            // Set version
            if(isset($props['installed_version'])){
                $temp['version'] = $props['installed_version'];
            }
            elseif(isset($props['version_to_install'])){
                $temp['version'] = $props['version_to_install'];
            }
            
            // Set installed size
            if(isset($props['installed_size'])){
                $temp['size'] = $props['installed_size'];
            }
            
            $this->id = 0;
            $this->merge($temp)->save();

        }

	}
}
