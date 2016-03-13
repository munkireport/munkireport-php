<?php

class Notification_model extends Model {
    
    function __construct($serial_number='')
    {
		parent::__construct('id', 'notification'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['unitid'] = 0; // Business unit this notification belongs to
        $this->rs['title'] = ''; // Human readable description
        $this->rs['created_by'] = ''; // Who made this?
        $this->rs['event_module'] = ''; // disk, certificate, 
        $this->rs['event_type'] = ''; // serial_number, network, etc.
        $this->rs['event_what'] = ''; // ZBB02BGGB, my_corp
        $this->rs['event_msg'] = ''; // The message 
        $this->rs['custom_filter'] = ''; // size < 5G, end_date < 1 month
        $this->rs['notify_type'] = ''; // userid, business_unit, etc.
        $this->rs['notify_who'] = ''; // bill, anne, my_corp
        $this->rs['notify_how'] = ''; // email, desktop notification
        $this->rs['last_notified'] = 0; // Last notification timestamp
        $this->rs['interval'] = 3600; // Seconds after which to notify again
        $this->rs['enabled'] = 1; // Active = 1, Inactive = 0
        $this->rs['timestamp'] = time(); // When was the filter enabled

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;

        $this->idx[] = array('unitid');
        $this->idx[] = array('event_module');
        $this->idx[] = array('event_what');
				
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

}
