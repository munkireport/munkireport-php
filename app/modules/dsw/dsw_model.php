<?php
class Dsw_model extends Model {

  function __construct($serial='') {
    parent::__construct('id', 'deploystudio'); // Primary key, tablename

    $this->rs['id'] = 0;
    $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
    $this->rs['workflow'] = '';

    $this->schema_version = 0;

    // Add indexes
    $this->idx[] = array('workflow');

    // Create table if it doesn't exist
    $this->create_table();

    if ($serial) {
      $this->retrieve_one('serial_number=?', $serial);
    }

    $this->serial = $serial;
  }

  function delete_set($serial) {
    $dbh=$this->getdbh();
    $sql = 'DELETE FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'serial_number' ).'=?';
    $stmt = $dbh->prepare( $sql );
    $stmt->bindValue( 1, $serial );
    $stmt->execute();
    return $this;
  }

  function process($data) {
    require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
    $parser = new CFPropertyList();
    $parser->parse($data);
    $plist = $parser->toArray();

    echo "Setting: '";
    echo $plist['ds_workflow'];
    echo "' as DeployStudio workflow name.\n";

    //$this->delete_set($this->serial);
    $this->workflow = '';
    $this->workflow = $plist['ds_workflow'];
    $this->save();
  }
}
