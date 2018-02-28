<?php

use CFPropertyList\CFPropertyList;

class Machine_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'machine'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['hostname'] = '';
        $this->rs['machine_model'] = '';
        $this->rs['machine_desc'] = '';
        $this->rs['img_url'] = '';
        $this->rs['cpu'] = '';
        $this->rs['current_processor_speed'] = '';
        $this->rs['cpu_arch'] = '';
        $this->rs['os_version'] = 0;
        $this->rs['physical_memory'] = 0;
        $this->rs['platform_UUID'] = '';
        $this->rs['number_processors'] = 0;
        $this->rs['SMC_version_system'] = '';
        $this->rs['boot_rom_version'] = '';
        $this->rs['bus_speed'] = '';
        $this->rs['computer_name'] = '';
        $this->rs['l2_cache'] = '';
        $this->rs['machine_name'] = '';
        $this->rs['packages'] = '';
        $this->rs['buildversion'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }

    /**
     * Get duplicate computernames
     *
     *
     **/
    public function get_duplicate_computernames()
    {
        $out = array();
        $filter = get_machine_group_filter();
        $sql = "SELECT computer_name, COUNT(*) AS count
				FROM machine
				LEFT JOIN reportdata USING (serial_number)
				$filter
				GROUP BY computer_name
				HAVING count > 1
				ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            $out[] = $obj;
        }

        return $out;
    }

    /**
     * Get model statistics
     *
     **/
    public function get_model_stats($summary)
    {
        $out = array();
        $filter = get_machine_group_filter();
        $sql = "SELECT count(*) AS count, machine_desc AS label
				FROM machine
				LEFT JOIN reportdata USING (serial_number)
				$filter
				GROUP BY machine_desc
				ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            $obj->label = $obj->label ? $obj->label : 'Unknown';
            $out[] = $obj;
        }

        // Check if we need to convert to summary (Model + screen size)
        if($summary){
            $model_list = array();
            foreach ($out as $key => $obj) {
                // Mac mini Server (Late 2012)
                //
                $suffix = "";
                if(preg_match('/^(.+) \((.+)\)/', $obj->label, $matches))
                {
                    $name = $matches[1];
                    // Find suffix
                    if(preg_match('/([\d\.]+-inch)/', $matches[2], $matches))
                    {
                        $suffix = ' ('.$matches[1].')';
                    }
                }
                else
                {
                    $name = $obj->label;

                }
                if(! isset($model_list[$name.$suffix]))
                {
                    $model_list[$name.$suffix] = 0;
                }
                $model_list[$name.$suffix] += $obj->count;

            }
            // Erase out
            $out = array();
            // Sort model list
            arsort($model_list);
            // Add entries to $out
            foreach ($model_list as $key => $count)
            {
                $out[] = array('label' => $key, 'count' => $count);
            }
        }

        return $out;
    }

    /**
     * Get memory statistics
     *
     *
     **/
    public function get_memory_stats()
    {
        $out = array();
        $sql = "SELECT physical_memory, count(1) as count
				FROM machine
				LEFT JOIN reportdata USING (serial_number)
				".get_machine_group_filter()."
				GROUP BY physical_memory
				ORDER BY physical_memory DESC";

        foreach ($this->query($sql) as $obj) {
            $obj->physical_memory = intval($obj->physical_memory);
            $obj->count = intval($obj->count);
            $out[] = $obj;
        }

        return $out;
    }

    // ------------------------------------------------------------------------

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

        $this->timestamp = time();
        $this->merge($mylist)->save();
    }
}
