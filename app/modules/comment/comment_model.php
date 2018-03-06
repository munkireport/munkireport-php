<?php
class Comment_model extends \Model
{
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'comment'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['section'] = ''; // Section of the comment
        $this->rs['user'] = ''; // username
        $this->rs['text'] = '';
        $this->rt['text'] = 'BLOB'; // Source text
        $this->rs['html'] = '';
        $this->rt['html'] = 'BLOB'; // Rendered text
        $this->rs['timestamp'] = 0; // Timestamp
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('section');
        $this->idx[] = array('user');
        
        // Create table if it does not exist
       //$this->create_table();
    }
}
