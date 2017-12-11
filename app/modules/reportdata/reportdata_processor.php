<?php

use CFPropertyList\CFPropertyList;

class Reportdata_processor
{
    private $serial_number;
    
    public function __construct($serial_number)
    {
        $this->serial_number = $serial_number;
    }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    public function process($plist)
    {
        $parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();

        // Remove serial_number from mylist, use the cleaned serial that was provided in the constructor.
        unset($mylist['serial_number']);

        // If console_user is empty, retain previous entry
        if (! $mylist['console_user']) {
            unset($mylist['console_user']);
        }

        // If long_username is empty, retain previous entry
        if (array_key_exists('long_username', $mylist) && empty($mylist['long_username'])) {
            unset($mylist['long_username']);
        }

        $model = Reportdata_model::updateOrCreate(
            ['serial_number' => $this->serial_number],
            $mylist
        );
        
        if ($model->wasRecentlyCreated) {
            store_event($this->serial_number, 'reportdata', 'info', 'new_client');
        }

    }

}