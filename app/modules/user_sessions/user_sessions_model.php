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
        $this->rs['remote_ssh'] = '';

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
        if ( ! $plist) {
            throw new Exception("Error Processing Request: No property list found", 1);
        }

        // Delete everything for serial user_sessions_keep_historical is not true
        if (!conf('user_sessions_keep_historical')) {
            $this->deleteWhere('serial_number=?', $this->serial_number);
        }
        
        $parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);
        $myList = $parser->toArray();

        $typeList = array(
            'event' => '',
            'time' => 0,
            'user' => '',
            'remote_ssh' => ''
        );

        foreach (array_reverse($myList) as $event) {

            if (array_key_exists('user', $event)) {
            // Check if user key exsits
                if ($event['user'] == "_mbsetupuser") {
                // Check if user is _mbsetupuser and skip that entry
                    continue;
                }
            }

            if (!conf('user_sessions_save_remote_ssh') && array_key_exists('remote_ssh', $event)) {
            // Check if remote_ssh key exists and skip if set to not save
                continue;
            }

            if (!conf('user_sessions_save_login') && $event['event'] == "login") {
            // Check if event is login and skip if set to not save
                continue;
            }

            if (!conf('user_sessions_save_logout') && $event['event'] == "logout") {
            // Check if event is logout and skip if set to not save
                continue;
            }

            if (!conf('user_sessions_save_reboot') && $event['event'] == "reboot") {
            // Check if event is reboot and skip if set to not save
                continue;
            }

            if (!conf('user_sessions_save_shutdown') && $event['event'] == "shutdown") {
            // Check if event is shutdown and skip if set to not save
                continue;
            }
            
            foreach ($typeList as $key => $value) {

                $this->rs[$key] = $value;

                if(array_key_exists($key, $event)) {
                    $this->rs[$key] = $event[$key];
                }
            }

            // Set the event type if remote_ssh key exists
            if (array_key_exists("remote_ssh", $event)){
                $this->rs["event"] = "sshlogin";
            }

            // Delete previous matches if user_sessions_keep_historical is true
            if (conf('user_sessions_keep_historical')) {
                $this->deleteWhere('serial_number=? AND time=? AND event=?', array($this->serial_number, $this->time, $this->event));
            }

            // Only save unique users if set to true
            if (conf('user_sessions_unique_users_only')) {
                $this->deleteWhere('serial_number=? AND user=?', array($this->serial_number, $this->user));
            }

            // Save user session event
            $this->id = '';
            $this->save();
        }
    }
}
