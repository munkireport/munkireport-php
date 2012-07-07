<?php

class Inventoryitem extends Model {
    
    function __construct($serial='')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial'] = (string) $serial;
        $this->rs['name'] = '';
        $this->rs['version'] = '';
        $this->rs['bundleid'] = '';
        $this->rs['bundlename'] = '';
        $this->rs['path'] = '';
		
		// Add indexes
		$this->idx['serial'] = array('serial');
		$this->idx['name_version'] = array('name', 'version');

		// Create table if it does not exist
        $this->create_table();
    }    
        
    function delete_set( $serial ) 
    {
        $dbh=$this->getdbh();
        $sql = 'DELETE FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'serial' ).'=?';
        $stmt = $dbh->prepare( $sql );
        $stmt->bindValue( 1, $serial );
        $stmt->execute();
        return $this;
    }
    
    function process($data)
    {    
        //list of bundleids to ignore
        $bundleid_ignorelist = isset($GLOBALS['bundleid_ignorelist']) ? $GLOBALS['bundleid_ignorelist'] : array('com.apple.print.PrinterProxy');

		// Compile regex
		$regex = '/^'.implode('|', $bundleid_ignorelist).'$/';
    
        if (! $this->serial) die('Serial missing');
                
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse(
            $data, CFPropertyList::FORMAT_XML);
        $inventory_list = $parser->toArray();
        if (count($inventory_list))
        {
            // clear existing inventory items
            $this->delete_set($this->serial);
            // insert current inventory items
            foreach ($inventory_list as $item)
            {
                if ( ! preg_match($regex, $item['bundleid']))
                {
                    $item['bundlename'] = isset($item['CFBundleName']) ? $item['CFBundleName'] : '';
                
                    $this->id = 0;
                    $this->merge($item)->save();
                }
            }
        }
    }
}