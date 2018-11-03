<?php

use CFPropertyList\CFPropertyList;

class User_sessions_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'user_sessions'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['event'] = '';
		$this->rs['time'] = 0;
		$this->rs['user'] = '';
		$this->rs['uid'] = NULL;
		$this->rs['remote_ssh'] = '';

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('event');
		$this->idx[] = array('time');
		$this->idx[] = array('user');
		$this->idx[] = array('uid');
		$this->idx[] = array('remote_ssh');

		// Create table if it does not exist
		//$this->create_table();

		$this->serial_number = $serial;
	}

	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author tuxudo
	 **/
	function process($plist)
	{
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}

		// Delete previous set
		$this->deleteWhere('serial_number=?', $this->serial_number);

		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();

		$typeList = array(
			'event' => '',
			'time' => 0,
			'user' => '',
			'uid' => NULL,
			'remote_ssh' => ''
		);

		foreach ($myList as $event) {
			foreach ($typeList as $key => $value) {

				$this->rs[$key] = $value;

				if(array_key_exists($key, $event))
				{
					$this->rs[$key] = $event[$key];
				}
			}

            if (array_key_exists("remote_ssh", $event)){
                $this->rs["event"] = "sshlogin";
            }

			// Save user session event
			$this->id = '';
			$this->save();
		}
	}
}
