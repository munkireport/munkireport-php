<?php

use CFPropertyList\CFPropertyList;

class Machine_processor
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

        // Set default computer_name
        if (! isset($mylist['computer_name']) or trim($mylist['computer_name']) == '') {
            $mylist['computer_name'] = 'No name';
        }

        // Convert memory string (4 GB) to int
        if (isset($mylist['physical_memory'])) {
            $mylist['physical_memory'] = intval($mylist['physical_memory']);
        }

        // Convert OS version to int
        if (isset($mylist['os_version'])) {
            $digits = explode('.', $mylist['os_version']);
            $mult = 10000;
            $mylist['os_version'] = 0;
            foreach ($digits as $digit) {
                $mylist['os_version'] += $digit * $mult;
                $mult = $mult / 100;
            }
        }

        // Dirify buildversion
        if (isset($mylist['buildversion'])) {
            $mylist['buildversion'] = preg_replace('/[^A-Za-z0-9]/', '', $mylist['buildversion']);
        }

        $model = Machine_model::updateOrCreate(
            ['serial_number' => $this->serial_number],
            $mylist
        );
    }

}