<?php
class Client extends Model {

	function __construct($serial='')
	{
		parent::__construct('id','client'); //primary key = uid; tablename = users
		$this->rs['id'] = '';
		$this->rs['name'] = '';
		$this->rs['serial'] = $serial;
		$this->rs['remote_ip'] = '';
		$this->rs['timestamp'] = '';
		$this->rs['runtype'] = '';
		$this->rs['runstate'] = '';
		$this->rs['console_user'] = '';
		$this->rs['errors'] = 0;
		$this->rs['warnings'] = 0;
		$this->rs['activity'] = array();
		$this->rs['report_plist'] = array();
		if ($serial)
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
	
	function update_report($plist)
	{		
		if ( ! $plist)
		{
			$this->activity = '';
            $this->errors = 0;
            $this->warnings = 0;
            $this->console_user = '';
            return $this;
		}
		
		include(APP_PATH .'lib/plistparser.php');
		$parser = new plistParser();
		$mylist = $parser->parseString($plist);
		
		# Save plist
		$this->report_plist = $mylist;
		
		# Check console user
		$this->console_user = isset($mylist['ConsoleUser']) ? $mylist['ConsoleUser'] : '';
        
        # Check errors and warnings
		$this->errors = isset($mylist['Errors']) ? count($mylist['Errors']) : 0;
		$this->warnings = isset($mylist['Warnings']) ? count($mylist['Warnings']) : 0;
		
		# Check activity
		$activity = array();
		foreach(array("ItemsToInstall", "InstallResults", "ItemsToRemove", "RemovalResults", "AppleUpdateList") AS $section)
		{
			if(isset($mylist[$section]) && $mylist[$section])
			{
				$activity[$section] = $mylist[$section];
			}
		}
		
		$this->activity = $activity ? $activity : '';
		
		return $this;
	}	
}    