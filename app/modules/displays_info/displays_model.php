<?php

class Displays_model extends Model {
    //todo fix next line?
    function __construct($monitor_serial='')
    {
      //todo all this
  		parent::__construct('monitor_serial', 'displays'); //primary key, tablename
  		$this->rs['monitor_serial'] = ''; // unique? string

      // +decide where to put VGA info

  		//indexes
      // monitor and machine serial at least
      $this->idx[] = array('allowedadmingroups');

  		// Schema version, increment when creating a db migration
      //todo should we start at 0?
  		$this->schema_version = 1;

  		// Create table if it does not exist
  		$this->create_table();

      //todo what's this?
  		if ($serial) {
  			$this->retrieve_one('serial_number=?', $serial);
  			$this->serial = $serial;
  		}
    } //end construct

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author Noel B.A.
     **/
	function process($data)
	{

		// process copied from network model. Translate strings to db fields. needed? . error proof?
          //todo use this as an example
        	$translate = array('Directory Service = ' => 'which_directory_service',
								'Active Directory Comments = ' => 'directory_service_comments');

    //todo all from here to the bottom :P

		//clear any previous data we had
		foreach($translate as $search => $field)
		{
			if(array_key_exists($field, $this->rt) && $this->rt[$field] == 'BOOL')
			{
				$this->$field = 0;
			}
			else
			{
				$this->$field = '';
			}
		}

		// Parse data
		foreach(explode("\n", $data) as $line) {
		    // Translate standard entries
			foreach($translate as $search => $field) {

			    if(strpos($line, $search) === 0) {

				    $value = substr($line, strlen($search));

				    // use bool when possible
				    if (strpos($value, 'Enabled') === 0) {
					    $this->$field = 1;
					    break;
				    } elseif (strpos($value, 'Disabled') === 0) {
					    $this->$field = 0;
					    break;
				    }

				    $this->$field = $value;
				    break;
			    }
			}

		} //end foreach explode lines
		$this->save();
	}
}
