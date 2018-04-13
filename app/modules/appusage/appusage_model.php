<?php
class Appusage_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'appusage'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['event'] = "";
        $this->rs['bundle_id'] = "";
        $this->rs['app_version'] = "";
        $this->rs['app_name'] = "";
        $this->rs['app_path'] = "";
        $this->rs['last_time_epoch'] = 0;
        $this->rs['last_time'] = "";
        $this->rs['number_times'] = 0;
    }

    // ------------------------------------------------------------------------

    /**
     * Get applications names for widget
     *
     **/
    public function get_applaunch()
    {
        $out = array();
        $sql = "SELECT SUM(number_times) AS count, app_name, event
                    FROM appusage
                    LEFT JOIN reportdata USING (serial_number)
                    ".get_machine_group_filter()."
                    AND event = 'launch'
                    AND app_name <> ''
                    GROUP BY app_name
                    ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->app_name = $obj->app_name ? $obj->app_name : 'Unknown';
                $out[] = $obj;
            }
        }

        return $out;
    }

    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/

    public function process($data)
    {
        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);
        
        // List of bundle IDs to ignore
        $bundleid_ignorelist = is_array(conf('appusage_ignorelist')) ? conf('appusage_ignorelist') : array();
        $regex = '/^'.implode('|', $bundleid_ignorelist).'$/';
                
        // Split into lines
        foreach(str_getcsv($data, "\n") as $line)
        {
            // Skip if empty line
            if(trim($line) === ''){
                continue;
            }

            // Split line
            $appusage_line = str_getcsv($line);

            if ( count($appusage_line) > 1)
            {
                // Check if we should skip this bundle ID
                if (preg_match($regex, $appusage_line[1])) {
                    continue;
                }
                
                $this->event = $appusage_line[0];
                $this->bundle_id = $appusage_line[1];
                $this->app_version = $appusage_line[2];
                $this->app_path = $appusage_line[3];

                $app_array_explode = explode('/',$appusage_line[3]);
                $app_array = array_pop($app_array_explode);
                $app_name_full = substr($app_array, 0, -4);
                $this->app_name = $app_name_full;

                $this->number_times = $appusage_line[4];
                $dt = new DateTime('@'.$appusage_line[5]);
                $this->last_time = ($dt->format('Y-m-d H:i:s'));
                $this->last_time_epoch = $appusage_line[5];

                $this->id = '';
                $this->create();
            }
        }
    } // end process()
}
