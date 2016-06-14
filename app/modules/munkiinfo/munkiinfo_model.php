<?php
class munkiinfo_model extends Model {

  function __construct($serial='')
  {
          parent::__construct('id', 'munkiinfo'); //primary key, tablename
          $this->rs['id'] = 0;
          $this->rs['serial_number'] = $serial;
          $this->rs['munkiinfo_key'] = '';
          $this->rs['munkiinfo_value'] = '';
        
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
  function process($plist)
  {
      require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
      $parser = new CFPropertyList();
      $parser->parse($plist);

      $plist = $parser->toArray();

      $this->delete_where('serial_number=?', $this->serial_number);
			$item = array_pop($plist);

			reset($item);
			while (list($key, $val) = each($item)) {
					$this->munkiinfo_key = $key;
					$this->munkiinfo_value = $val;

					$this->id = '';
					$this->save();

			}
  }
}
