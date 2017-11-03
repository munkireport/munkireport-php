<?php

// Remove fake nulls and set them to NULL

class Migration_munkireportinfo_remove_fake_null extends \Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'munkireportinfo';
    }

    public function up()
    {
        // Set Nulls
         foreach (array('version') as $item)
        {    
            $sql = 'UPDATE munkireportinfo 
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
