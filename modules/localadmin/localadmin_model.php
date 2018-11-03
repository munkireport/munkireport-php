<?php
class Localadmin_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'localadmin'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['users'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }

    // ------------------------------------------------------------------------

     public function get_localadmin()
     {
        $out = array();
        //Check if config threshold is set for number of admins to show
        $threshold=2;
        if(conf('local_admin_threshold') != '') {
            $threshold=conf('local_admin_threshold');
        }
        $filter = get_machine_group_filter();
        $sql = "SELECT machine.serial_number, computer_name,
                    LENGTH(users) - LENGTH(REPLACE(users, ' ', '')) + 1 AS count,
                    users
                    FROM localadmin
                    LEFT JOIN machine USING (serial_number)
                    LEFT JOIN reportdata USING (serial_number)
                    $filter
                    AND localadmin.users LIKE '%'
                    ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            if ($obj->count >= $threshold) {
                $obj->users = $obj->users ? $obj->users : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author AvB
     **/
    public function process($data)
    {
        $this->users = trim($data);
        $this->save();
    }
}
