<?php
class Dsw_model extends Model {

  function __construct($serial='') {
    parent::__construct('id', 'deploystudiow'); // Primary key, tablename

    $this->rs['id'] = 0; // TODO Why is primary key set to 0?
    $this->rs['serial_number'] = ''; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
    $this->rs['ds_workflow'] = '';

    // Create table if it doesn't exist
    $this->create_table();

    if ($serial) {
      $this->retrieve_one('serial_number=?', $serial);
    }

    $this->serial = $serial;
  }

  function process($data) {
    require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
    $parser = new CFPropertyList();
    $parser->parse($data);

    $plist = $parser->toArray();

    print_r($plist);

    if (isset($plist['ds_workflow'])) {
      $this->ds_workflow = $plist['ds_workflow'];
    }
    else {
      $this->$item = '';
    }

    $this->save();
  }
}
