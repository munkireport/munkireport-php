<?php

/**
 * Migration class
 *
 * @package munkireport
 * @author AvB
 **/
class Migration extends Model
{
	function __construct($table_name = '')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['table_name'] = ''; $this->rt['table_name'] = 'VARCHAR(255) UNIQUE';
        $this->rs['version'] = 0;
				
		// Create table if it does not exist
        $this->create_table();
        
        if($table_name)
        {
            $this->retrieve_one('table_name=?', array($table_name));
            $this->table_name = $table_name;
        }
        
        return $this;
    }

} // END class 
