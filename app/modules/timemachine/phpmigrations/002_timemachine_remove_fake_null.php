<?php

// Remove fake nulls and set them to NULL

class Migration_timemachine_remove_fake_null extends \Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'timemachine';
    }

    public function up()
    {
        // Set Nulls
         foreach (array('destinations','snapshot_count','is_network_destination','skip_system_files','mobile_backups','bytes_used','bytes_available','auto_backup','always_show_deleted_backups_warning','duration') as $item)
        {    
            $sql = 'UPDATE timemachine 
            SET '.$item.' = NULL
            WHERE '.$item.' = -9876543 OR '.$item.' = -9876540';
            $this->exec($sql);
        }   
    }

    public function down()
    {
        throw new Exception("Can't go back", 1);
    }
}
