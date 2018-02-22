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

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;

        //indexes to optimize queries
        $this->idx[] = array('event');
        $this->idx[] = array('bundle_id');
        $this->idx[] = array('app_version');
        $this->idx[] = array('app_path');
        $this->idx[] = array('app_name');
        $this->idx[] = array('last_time_epoch');
        $this->idx[] = array('last_time');
        $this->idx[] = array('number_times');

        // Create table if it does not exist
       //$this->create_table();
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

        // Split into lines
        foreach(str_getcsv($data, "\n") as $line)
        {
            if(trim($line) === ''){
                continue;
            }

            // Split line
            $appusage_line = str_getcsv($line);

            if ( count($appusage_line) > 1)
            {
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
