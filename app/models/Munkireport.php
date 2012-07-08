<?php
class Munkireport extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = 0;
		$this->rs['serial'] = $serial; $this->rt['serial'] = 'VARCHAR(255) UNIQUE';
		$this->rs['remote_ip'] = '';
		$this->rs['timestamp'] = '';
		$this->rs['runstate'] = '';
		$this->rs['console_user'] = '';
		$this->rs['errors'] = 0;
		$this->rs['warnings'] = 0;
		$this->rs['activity'] = array();
		$this->rs['report_plist'] = array();
		
		// Create table if it does not exist
        $this->create_table();
        
		if ($serial)
		{
		    $this->retrieve($serial);
            if(!$this->rs['serial'])
            {
                $this->serial = $serial;
            }
		}
		  $this->retrieve($serial);
		  
	}		
		
	function retrieve( $serial ) 
	{
		$dbh=$this->getdbh();
		$sql = 'SELECT * FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'serial' ).'=?';
		$stmt = $dbh->prepare( $sql );
		$stmt->bindValue( 1, $serial );
		$stmt->execute();
		$rs = $stmt->fetch( PDO::FETCH_ASSOC );
		if ( $rs )
			foreach ( $rs as $key => $val )
				if ( isset( $this->rs[$key] ) )
					$this->rs[$key] = is_scalar( $this->rs[$key] ) ? $val : unserialize( $this->COMPRESS_ARRAY ? gzinflate( $val ) : $val );
		return $this;
	}
	
	
	function process($plist)
	{		
		$this->req = 'postflight';
		$this->timestamp = date('Y-m-d H:i:s');
		$this->remote_ip = $_SERVER['REMOTE_ADDR'];
		
		if ( ! $plist)
		{
			$this->activity = '';
            $this->errors = 0;
            $this->warnings = 0;
            $this->console_user = '';
            return $this;
		}
		

		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();
				
		# Save plist
		$this->report_plist = $mylist;
		
		# Check console user
		$this->console_user = isset($mylist['ConsoleUser']) ? $mylist['ConsoleUser'] : '';
        
        # Check errors and warnings
		$this->errors = isset($mylist['Errors']) ? count($mylist['Errors']) : 0;
		$this->warnings = isset($mylist['Warnings']) ? count($mylist['Warnings']) : 0;
		
		# Check activity
		$activity = array();
		foreach(array("ItemsToInstall", "InstallResults", "ItemsToRemove", "RemovalResults", "AppleUpdates") AS $section)
		{
			if(isset($mylist[$section]) && $mylist[$section])
			{
				$activity[$section] = $mylist[$section];
			}
		}
		
		$this->activity = $activity ? $activity : '';
				
		$this->save();
		
		return $this;
	}	
}