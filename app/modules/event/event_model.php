<?php

/**
 * Event class
 *
 * This is the central place to store events from
 * a client.
 *
 * @package munkireport
 * @author AvB
 **/
class Event_model extends Model
{
	function __construct($serial_number = '', $module = '')
    {
		parent::__construct('id', 'event'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = ''; $this->rt['serial_number'] = 'VARCHAR(30)';
        $this->rs['type'] = ''; $this->rt['type'] = 'VARCHAR(10)';
        $this->rs['module'] = ''; $this->rt['module'] = 'VARCHAR(20)';
		$this->rs['msg'] = '';
		$this->rs['data'] = '';
        $this->rs['timestamp'] = time();
		
		$this->idx[] = array('serial_number');
		$this->idx[] = array('serial_number', 'module');
		$this->idx[] = array('type');
		$this->idx[] = array('msg');


		// Create table if it does not exist
        $this->create_table();
        
        if($serial_number && $module)
        {
            $this->retrieve_one('serial_number=? AND module=?', array($serial_number, $module));
			$this->serial_number = $serial_number;
			$this->module = $module;
        }
        
        return $this;
    }

    /**
     * Reset events
     *
     * @param string serial number
     * @param string optional module
     * @author 
     **/
    function reset($serial_number = '', $module = '')
    {
        $where_params = array($serial_number);
        $where_string = ' WHERE serial_number=?';

        if($module)
        {
            $where_params[] = $module;
            $where_string .= ' AND module=?';
        }

        $sql = "DELETE FROM $this->tablename $where_string";
        $stmt = $this->prepare( $sql );

        return $stmt->execute($where_params);

    }
	
	/**
	 * Store message
	 *
	 * Store a message
	 *
	 * @param string $type message type
	 * @param string $type message
	 **/
	public function store($type, $msg, $data = '')
	{
		$this->type = $type;
		$this->msg = $msg;
		$this->data = $data;
        $this->timestamp = time();
		$this->save();
	}

    /**
     * Add message
     *
     * @param string type
     * @param string type
     * @author AvB
     **/
    function danger($module, $msg)
    {

    }

} // END class 
