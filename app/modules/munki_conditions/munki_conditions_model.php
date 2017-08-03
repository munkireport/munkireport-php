<?php
class munki_conditions_model extends Model
{

    public function __construct($serial = '')
    {
          parent::__construct('id', 'munki_conditions'); //primary key, tablename
          $this->rs['id'] = 0;
          $this->rs['serial_number'] = $serial;
          $this->rs['condition_key'] = '';
          $this->rs['condition_value'] = '';
        
          // Schema version, increment when creating a db migration
          $this->schema_version = 0;
        
          // Add indexes
          $this->idx[] = array('serial_number');

          // Create table if it does not exist
          $this->create_table();
            
        if ($serial) {
            $this->retrieve_record($serial);
          
            $this->serial = $serial;
        }
    }

  /**
   * Process data sent by postflight
   *
   * @param string data
   * @author erikng
   **/
    public function process($plist)
    {
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse($plist);

        $plist = $parser->toArray();

        $this->deleteWhere('serial_number=?', $this->serial_number);
            $item = array_pop($plist);

            reset($item);
        while (list($key, $val) = each($item)) {
                $this->condition_key = $key;
                $this->condition_value = $val;

                $this->id = '';
                $this->save();
        }
    }
}