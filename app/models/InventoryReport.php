<?php

class InventoryReport extends Model {
    
    function __construct($serial='')
    {
        parent::__construct('id','inventory'); //primary key = id; tablename = inventory
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['timestamp'] = time();
        $this->rs['sha256hash'] = '';
				
		// Create table if it does not exist
        $this->create_table();

        if ($serial)
          $this->retrieve($serial);
    }       

    function retrieve( $serial ) 
    {
        $dbh=$this->getdbh();
        $sql = 'SELECT * FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'serial_number' ).'=?';
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
