<?php
class InstallHistory extends Model {

	function __construct($serial_number='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial_number; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['date'] = 0;
		$this->rs['displayName'] = '';
		$this->rs['displayVersion'] = '';
		$this->rs['packageIdentifiers'] = '';
		$this->rs['processName'] = '';
		
		// Create table if it does not exist
		$this->create_table();
		  
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Delete all items with serialnumber
	 *
	 * @author abn290
	 **/
	function delete_set() 
	{
		$dbh=$this->getdbh();
		$sql = 'DELETE FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'serial_number' ).'=?';
		$stmt = $dbh->prepare( $sql );
		$stmt->bindValue( 1, $this->serial_number );
		$stmt->execute();
		return $this;
	}
		
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author abn290
	 **/
	function process($plist)
	{
		// Delete old data
		$this->delete_set();
		
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();

		foreach($mylist as $item)
		{
			// PackageIdentifiers is an array, so we only retain one
			// packageidentifier so we can differentiate between
			// Apple and third party tools
			if(array_key_exists('packageIdentifiers', $item))
			{
				$item['packageIdentifiers'] = array_pop($item['packageIdentifiers']);
			}
			$this->id = 0;
			$this->merge($item)->save();
		}
	}




	/**
	 * Return all install items for the given serial number
	 */
	public function itemsBySerialNumber($aSerialNumber)
	{
		return $this->retrieve_many('serial_number=? ORDER BY date DESC', $aSerialNumber);
	}
}