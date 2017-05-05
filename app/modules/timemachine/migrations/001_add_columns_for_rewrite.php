<?php

class Migration_add_columns_for_rewrite extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'timemachine';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();
        
        // Wrap in transaction
        $dbh->beginTransaction();
        
        // Add new columes
        foreach (array('consistency_scan_date','date_of_latest_warning','destination_id','destination_uuids','last_known_encryption_state','result','root_volume_uuid','snapshot_dates','exclude_by_path','host_uuids','last_configuration_trace_date','last_destination_id','localized_disk_image_volume_name','skip_paths','alias_volume_name','earliest_snapshot_date','latest_snapshot_date','mount_point','network_url','server_display_name','time_capsule_display_name','volume_display_name','bytes_available','bytes_used') as $item) {
                        
            // Adding a column is simple...
            if ($item === "bytes_available" || $item === 'bytes_used'){
                $sql = sprintf(
                'ALTER TABLE %s ADD COLUMN '.$item.' BIGINT',
                $this->enquote($this->tablename)
            ); 
            $this->exec($sql); 
                            
            } else if ($item === "destination_uuids" || $item === 'snapshot_dates' || $item === 'exclude_by_path' || $item === 'skip_paths') {    
            $sql = sprintf(
                'ALTER TABLE %s ADD COLUMN '.$item.' TEXT',
                $this->enquote($this->tablename)
            );
                
            $this->exec($sql);

            } else {    
            $sql = sprintf(
                'ALTER TABLE %s ADD COLUMN '.$item.' VARCHAR(255)',
                $this->enquote($this->tablename)
            );
            $this->exec($sql); 

            }
            
            // Exclude come columns from being indexed, otherwise MySQL will complain about too many indexes
            $excludeindex = array('destination_uuids','snapshot_dates','exclude_by_path','skip_paths');
            if (! in_array($item, $excludeindex)) {
                
            // ...so is adding an index
            $sql = sprintf("CREATE INDEX ".$item." ON %s (".$item.")",
                $this->enquote($this->tablename)
            );
            $this->exec($sql); 

            }
        }
        
        // Add new INT(11) columns
        foreach (array('always_show_deleted_backups_warning','auto_backup','mobile_backups','skip_system_files','is_network_destination','snapshot_count','destinations') as $item) {
            
            // Adding a column is simple...
            $sql = sprintf(
                'ALTER TABLE %s ADD COLUMN '.$item.' INT(11)',
                $this->enquote($this->tablename)
            );
            $this->exec($sql); 
                
            // ...so is adding an index
            $sql = sprintf(
                "CREATE INDEX ".$item." ON %s (".$item.")",
                $this->enquote($this->tablename)
            );
            $this->exec($sql); 
        }
        
        // Add indexes for exising columns
        foreach (array('last_failure_msg','duration') as $item) {
            
            // sqlite and MySQL have slightly different create index values
            if ( $this->get_driver() == 'sqlite') {
                // Add the index
                $sql = sprintf(    
                    "CREATE INDEX ".$item." ON %s (".$item.")",
                    $this->enquote($this->tablename)
                );
             } else {
                // Add the index
                $sql = sprintf(    
                    "ALTER TABLE %s ADD INDEX(`".$item."`)",
                    $this->enquote($this->tablename)
                );
             } 
            $this->exec($sql);

        }
        
        $dbh->commit();
    }

    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                $dbh->beginTransaction();

                // Create temporary table
                $sql = "CREATE TABLE %_temp (id INTEGER PRIMARY KEY, serial_number VARCHAR(255) UNIQUE, last_success VARCHAR(255), last_failure VARCHAR(255), last_failure_msg VARCHAR(255), duration INTEGER, timestamp VARCHAR(255))";
                $this->exec(sprintf($sql, $this->tablename));

                $sql = "INSERT INTO %_temp 
							SELECT id, serial_number, last_success, last_failure, last_failure_msg, duration, timestamp
							FROM %s";
                $this->exec(sprintf($sql, $this->tablename, $this->tablename));

                $sql = "DROP table %s";
                $this->exec(sprintf($sql, $this->tablename));

                $sql = "ALTER TABLE %_temp RENAME TO %s";
                $this->exec(sprintf($sql, $this->tablename, $this->tablename));

                $dbh->commit();

                break;

            case 'mysql':
                // MySQL drops the index as well -> check for other engines
                foreach (array('consistency_scan_date','date_of_latest_warning','destination_id','destination_uuids','last_known_encryption_state','result','root_volume_uuid','snapshot_dates','exclude_by_path','host_uuids','last_configuration_trace_date','last_destination_id','localized_disk_image_volume_name','skip_paths','alias_volume_name','earliest_snapshot_date','latest_snapshot_date','mount_point','network_url','server_display_name','time_capsule_display_name','volume_display_name','bytes_available','bytes_used','always_show_deleted_backups_warning','auto_backup','mobile_backups','skip_system_files','is_network_destination','snapshot_count','destinations') as $item) {
                $sql = sprintf(
                    'ALTER TABLE %s DROP COLUMN '.$item,
                    $this->enquote($this->tablename)
                );
                $this->exec($sql);
                }
            
            default:
                # code here...
                break;
        }
    }
}
