<?php

class Notification_model extends Model {
    
    function __construct()
    {
		parent::__construct('id', 'notification'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = ''; // serial_number
        $this->rs['notification_title'] = ''; // Human readable description
        $this->rs['notification_creator'] = ''; // Who made this?
        $this->rs['notification_module'] = ''; // disk, certificate, *
        $this->rs['notification_msg'] = ''; // munki.warning, new_client, *
        $this->rs['notification_severity'] = ''; // danger, warning, success, * 
        $this->rs['notification_who'] = ''; // bill, anne, my_corp
        $this->rs['notification_how'] = ''; // email, desktop notification
        $this->rs['business_unit'] = 0; // User is part of this BU
        $this->rs['event_obj'] = ''; $this->rt['notification_json'] = 'BLOB'; // JSON object with last notification data
        $this->rs['run_interval'] = 3600; // Seconds after which to notify again
        $this->rs['last_run'] = 0; // Last run timestamp
        $this->rs['suspended_until'] = 0; // Suspend notification until timestamp
        $this->rs['notification_enabled'] = 1; // Active = 1, Inactive = 0
        $this->rs['last_notified'] = 0; // Last notification timestamp
        $this->rs['timestamp'] = time(); // When was the filter enabled

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;

        //$this->idx[] = array('unitid');
				
		// Create table if it does not exist
        $this->create_table();
                
        return $this;
    }
    
    /**
     * Get list of notification objects
     *
     * Returns an array of notification objects for the current business unitid
     * or all objects if admin
     *
     * @param type var Description
     **/
    public function get_list()
    {
        $where = '';
        if (array_key_exists('business_unit', $_SESSION)) {
            $where = sprintf('WHERE business_unit=%d', $_SESSION['business_unit']);
        }
        $sql = "SELECT * FROM notification $where";
        return $this->query($sql);        
    }
    
    public function save($data)
    {
        $id = array_key_exists('id', $data) ? $data['id'] : '';
        $this->retrieve($id);

        // if business unit in session, overwrite business unit in data or reject?
        if (array_key_exists('business_unit', $_SESSION))
        {
            $data['business_unit'] = $_SESSION['business_unit'];
        }
        
        // TODO validate $data
        $this->merge($data);
                
        return parent::save();
    }
    
    /**
     * retrieve override
     *
     * Checks membership of bu
     *
     * @param integer $id Notification id
     * @return object model
     */
    public function retrieve($id='')
    {
        $wherewhat = 'id=?';
        $bindings = array($id);
        if (array_key_exists('business_unit', $_SESSION)) {
            $wherewhat .= sprintf(' AND business_unit=%d', $_SESSION['business_unit']);
        }
        
        $this->retrieve_one( $wherewhat, $bindings );
        return $this;
    }
    
    /**
     * Get list of notifications to check
     *
     * Check which notifications should be checked against the event list
     *
     **/
    public function getDueNotifications()
    {
        $where = sprintf('notification_enabled = 1
            AND (last_run+run_interval) < %d
            AND suspended_until < %d',
            time(), time()
        );
        return $this->retrieve_many($where);
    }

}
