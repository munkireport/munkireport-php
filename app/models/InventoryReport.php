<?php

class InventoryReport extends Model {
    
    function create_table_if_missing() {
        $dbh = getdbh();
        if( ! $dbh->prepare( "SELECT * FROM 'inventory' LIMIT 1" ))
        {
            $dbh->exec('DROP TABLE "inventory"');
            $dbh->exec('VACUUM');

            $sql = "CREATE TABLE `inventory` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                `serial` varchar(255) NOT NULL,
                `timestamp` INTEGER NULL,
                `sha256hash` varchar(255) NULL
            ) ";
            $rowsaffected = $dbh->exec($sql);
        }
        return ($dbh->errorCode() == '00000');
    }

    function __construct($serial='')
    {
        parent::__construct('id','inventory'); //primary key = id; tablename = inventory
        $this->rs['id'] = '';
        $this->rs['serial'] = $serial;
        $this->rs['timestamp'] = time();
        $this->rs['sha256hash'] = '';
        $this->create_table_if_missing();
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
}
