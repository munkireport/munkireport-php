<?php

// Remove fake nulls and set them to NULL

class Migration_smart_stats_remove_another_fake_null extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'smart_stats';
    }

    public function up()
    {
        // Set Nulls
         foreach (array('error_count') as $item)
        {
            $sql = 'UPDATE smart_stats
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
