<?php

class InventoryItem extends Model {
    
    function create_table_if_missing() {
        $dbh = getdbh();
        if( ! $dbh->prepare( "SELECT * FROM 'inventoryitem' LIMIT 1" ))
        {
            $dbh->exec('DROP TABLE "inventoryitem"');
            $dbh->exec('VACUUM');

            $sql = "CREATE TABLE `inventoryitem` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                `serial` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `version` varchar(255) NULL,
                `bundleid` varchar(255) NULL,
                `bundlename` varchar(255) NULL,
                `path` varchar(1024) NOT NULL
            ) ";
            $rowsaffected = $dbh->exec($sql);
        }
        return ($dbh->errorCode() == '00000');
    }

    function __construct($serial='')
    {
        parent::__construct('id','inventoryitem'); //primary key = id; tablename = inventoryitem
        $this->rs['id'] = '';
        $this->rs['serial'] = $serial;
        $this->rs['name'] = '';
        $this->rs['version'] = '';
        $this->rs['bundleid'] = '';
        $this->rs['bundlename'] = '';
        $this->rs['path'] = '';
        $this->create_table_if_missing();
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