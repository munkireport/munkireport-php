<?php

use CFPropertyList\CFPropertyList;

class Munki_facts_model extends Model
{

    public function __construct($serial = '')
    {
          parent::__construct('id', 'munki_facts'); //primary key, tablename
          $this->rs['id'] = 0;
          $this->rs['serial_number'] = $serial;
          $this->rs['fact_key'] = '';
          $this->rt['fact_key'] = 'TEXT';
          $this->rs['fact_value'] = '';
          $this->rt['fact_value'] = 'TEXT';
        
          // Schema version, increment when creating a db migration
          $this->schema_version = 0;
        
          // Add indexes
          $this->idx[] = array('serial_number');

          // Create table if it does not exist
         //$this->create_table();
            
        if ($serial) {
            $this->retrieve_record($serial);
          
            $this->serial = $serial;
        }
    }

     public function get_facts_report()
     {
        $out = array();
        $sql = "SELECT fact_key, COUNT(1) AS count
                FROM munki_facts
                GROUP BY fact_key
                ORDER BY COUNT DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->fact_key = $obj->fact_key ? $obj->fact_key : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }

  /**
   * Process data sent by postflight
   *
   * @param string data
   * @author erikng
   **/
    public function process($plist)
    {
        $parser = new CFPropertyList();
        $parser->parse($plist);

        $plist = $parser->toArray();
        $this->deleteWhere('serial_number=?', $this->serial_number);
        while (list($key, $val) = each($plist)) {
                $this->fact_key = $key;
                $this->fact_value = $val;

                $this->id = '';
                $this->save();
        }
    }
}
