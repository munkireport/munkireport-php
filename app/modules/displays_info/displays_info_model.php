<?php

class Displays_info_model extends Model {
    //todo fix this line?
    function __construct($display_serial='')
    {
      //todo all this
      parent::__construct('id', 'displays'); //primary key, tablename
          $this->rs['id'] = '';
          $this->rs['display_serial'] = $display_serial; // Serial num of the display
          $this->rs['machine_serial'] = ''; // Serial num of the computer
          $this->rs['vendor'] = ''; // Vendor for the display
          $this->rs['model'] = ''; // Model of the display
          $this->rs['manufactured'] = ''; // Aprox. date when it was built
          $this->rs['native'] = ''; // Native resolution
          $this->rs['timestamp'] = 0; // Unix time from the computer when report was generated

      //indexes to optimize queries
      $this->idx[] = array('display_serial');
      $this->idx[] = array('machine_serial');

      // Schema version, increment when creating a db migration
      $this->schema_version = 0;

      // Create table if it does not exist
      $this->create_table();

    } //end construct

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author Noel B.A.
     **/
    function process($data)
    {

      // translate array used to match data to db fields
      $translate = array('Serial = ' => 'display_serial',
                          'Vendor = ' => 'vendor',
                          'Model = ' => 'model',
                          'Manufactured = ' => 'vendor',
                          'Native = ' => 'native');

      // Parse data
      foreach(explode("\n", $data) as $line) {
        // Translate standard entries
        foreach($translate as $search => $field) {

            if(strpos($line, $search) === 0) {

              $value = substr($line, strlen($search));
              $this->$field = $value;
              break;
            }

        } //end foreach translate
      //todo timestamp to be added by the server
      //todo machine_serial to be added by the server
      //todo separator line makes us jump to next display!
      $this->save(); //save after each display
      } //end foreach explode lines

      //todo echo the result back to the client

    } //process function end

} // Displays_info_model end
