<?php
class Tag_model extends Model
{
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'tag'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['tag'] = ''; // Tag
        $this->rs['user'] = ''; // username
        $this->rs['timestamp'] = 0; // Timestamp
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('tag');
        $this->idx[] = array('user');
        
        // Create table if it does not exist
        $this->create_table();
    }
    
    /**
     * Retrieve all tags
     *
     * @param $add_count (boolean) Add counts
     **/
    public function all_tags($add_count = false)
    {
        $out = array();
        $filter = get_machine_group_filter();
        
        $sql = "SELECT tag, count(*) as cnt
			FROM tag
			LEFT JOIN reportdata USING (serial_number)
			$filter
			GROUP BY tag
            ORDER BY tag ASC";
        foreach ($this->query($sql) as $obj) {
            if ($add_count) {
                $out[]  = $obj;
            } else {
                $out[]  = $obj->tag;
            }
        }
        return $out;
    }
}
