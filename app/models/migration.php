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

    /**
     * Migrate to current schema version
     *
     * @param		string		tablename
     * @param		int			requested version
     * @return		mixed
     **/
    public function current($model_name)
    {
    	$model = new $model_name;

    	// Check if model uses the same table
    	if($model->get_table_name() != $this->table_name)
    	{
    		die('Migrate error: model '.$model_name.
    			' does not reference '.$this->table_name);
    	}

    	// Get model version
    	$model_version = $model->get_version();

    	if($req_version == $cur_version)
    	{
    		return TRUE;
    	}
    }
} // END class 
