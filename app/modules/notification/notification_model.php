<?php

class Notification_model extends Model {
    
    function __construct($serial_number='')
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
        $sql = 'SELECT * FROM notification';
        return $this->query($sql);        
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
