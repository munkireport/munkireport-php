<?php

class InventoryItem extends Model {
    
    function __construct($serial='')
    {
        parent::__construct('id','inventoryitem'); //primary key = id; tablename = inventoryitem
        $this->rs['id'] = 0;
        $this->rs['serial'] = $serial;
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
}