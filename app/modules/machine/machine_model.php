<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Machine_model extends Eloquent
{

    protected $table = 'machine';
    public $timestamps = false;
    protected $fillable
        = [
            'serial_number',
            'hostname',
            'machine_model',
            'machine_desc',
            'img_url',
            'cpu',
            'current_processor_speed',
            'cpu_arch',
            'os_version',
            'physical_memory',
            'platform_UUID',
            'number_processors',
            'SMC_version_system',
            'boot_rom_version',
            'bus_speed',
            'computer_name',
            'l2_cache',
            'machine_name',
            'packages',
            'buildversion',
        ];

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
}
