<?php
class Installhistory_model extends Model {

	function __construct($serial_number='')
	{
		parent::__construct('id', 'installhistory'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial_number;
		$this->rs['date'] = 0;
		$this->rs['displayName'] = '';
		$this->rs['displayVersion'] = '';
		$this->rs['packageIdentifiers'] = '';
		$this->rs['processName'] = '';

		// Schema version, increment when creating a db migration
		$this->schema_version = 1;
		
		$this->idx['serial_number'] = array('serial_number');

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

		// Check if we're passed a plist (10.6 and higher)
		if(strpos($plist, '<?xml version="1.0" encoding="UTF-8"?>') === 0)
		{
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
		else // 10.5 Software Update Log
		{
			//2007-12-14 12:40:47 +0100: Installed "GarageBand Update" (4.1.1)
			$pattern = '/^(.*): .*"(.+)"\s+\((.+)\)/m';
			if(preg_match_all($pattern, $plist, $result, PREG_SET_ORDER))
			{
				$this->packageIdentifiers = 'com.apple.fake';
				$this->processName = 'installer';

				foreach ($result as $row)
				{
					$this->date = strtotime($row[1]);
					$this->displayName = $row[2];
					$this->displayVersion = $row[3];
					$this->id = 0;
					$this->save();
				}
			}			
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