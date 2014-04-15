<?php

class Displays_info_model extends Model {

    function __construct($serial='')
    {
      parent::__construct('id', 'displays'); //primary key, tablename
          $this->rs['id'] = '';
          $this->rs['type'] = 0; // Built-in = 0 ; External =1
          $this->rs['display_serial'] = ''; // Serial num of the display, if any
          $this->rs['serial_number'] = $serial; // Serial num of the computer
          $this->rs['vendor'] = ''; // Vendor for the display
          $this->rs['model'] = ''; // Model of the display
          $this->rs['manufactured'] = ''; // Aprox. date when it was built
          $this->rs['native'] = ''; // Native resolution
          $this->rs['timestamp'] = 0; // Unix time when the report was uploaded

      //indexes to optimize queries
      $this->idx[] = array('display_serial');
      $this->idx[] = array('serial_number');

      // Schema version, increment when creating a db migration
      $this->schema_version = 0;

      // Create table if it does not exist
      $this->create_table();

      if($serial){
        $this->retrieve_one('serial_number=?', $serial);
      }

      $this->serial = $serial;

    } //end construct


// ------------------------------------------------------------------------

/**
 * Delete any known entry for display_serial
 *
 * @author Noel B.A.
 **/
function delete_set()
{
  $dbh=$this->getdbh();
  $sql = 'DELETE FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'display_serial' ).'=?';
  $stmt = $dbh->prepare( $sql );
  $stmt->bindValue( 1, $this->display_serial );
  $stmt->execute();
  return $this;
}

// ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author Noel B.A.
     **/
    function process($data)
    {

      // translate array used to match data to db fields
      $translate = array('Type = ' => 'type',
                          'Serial = ' => 'display_serial',
                          'Vendor = ' => 'vendor',
                          'Model = ' => 'model',
                          'Manufactured = ' => 'manufactured',
                          'Native = ' => 'native');

      // Parse data
      foreach(explode("\n", $data) as $line) {
        // Translate standard entries
        foreach($translate as $search => $field) {

          //the separator is what triggers the save for each display
          if(strpos($line, '----------') === 0) {
            if($this->type === 1){ //only delete if External
              $this->id = 0;
              $this->delete_set();
            }
            $this->save(); //the actual save
            break;
          } elseif(strpos($line, $search) === 0) {
            $value = substr($line, strlen($search));
            // use bool for Type
            if (strpos($value, 'Internal') === 0) {
              $this->$field = 0;
              break;
            } elseif (strpos($value, 'External') === 0) {
              $this->$field = 1;
              break;
            }

            $this->$field = $value;
            break;
          }

        } //end foreach translate

      //timestamp added by the server
      $this->timestamp = time();

      } //end foreach explode lines

    } //process function end

} // Displays_info_model end
