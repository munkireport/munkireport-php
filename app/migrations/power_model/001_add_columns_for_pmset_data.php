<?php

class Migration_add_columns_for_pmset_data extends Model
{
    protected $columname1 = 'sleep_prevented_by'; //varchar(1024)

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'power';
    }

    public function up()
    {
                
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();
        
        // Adding a column is simple...
        $sql = sprintf(
            'ALTER TABLE %s ADD COLUMN %s VARCHAR(1024)',
            $this->enquote($this->tablename),
            $this->enquote($this->columname1)
        );

        $this->exec($sql);

        // so is adding an index...
        $idx_name = $this->tablename . '_' . $this->columname1;
        $sql = sprintf(
            "CREATE INDEX %s ON %s (%s)",
            $idx_name,
            $this->enquote($this->tablename),
            $this->columname1
        );

        $this->exec($sql);
        
        // Add new VARCHAR(255) columes
        foreach (array('hibernatefile','schedule','adapter_id','family_code','adapter_serial_number','combined_sys_load','user_sys_load','thermal_level','battery_level','ups_name','active_profile','ups_charging_status','externalconnected','cellvoltage','manufacturer','batteryserialnumber','fullycharged','ischarging','voltage') as $item) {
                        
            // Adding a column is simple...
            if ($item == "voltage"){}
                $sql = sprintf(
                'ALTER TABLE %s ADD COLUMN '.$item.' FLOAT',
                $this->enquote($this->tablename)
            ); 
            } else {    
            $sql = sprintf(
                'ALTER TABLE %s ADD COLUMN '.$item.' VARCHAR(255)',
                $this->enquote($this->tablename)
            );
            }
            $this->exec($sql);
            
            // ...so is adding an index
            $sql = sprintf("CREATE INDEX ".$item." ON %s (".$item.")",
                $this->enquote($this->tablename)
            );
            $this->exec($sql);  
        }

        // Add new INT(11) columns
        foreach (array('standbydelay','standby','womp','halfdim','gpuswitch','sms','networkoversleep','disksleep','sleep','autopoweroffdelay','hibernatemode','autopoweroff','ttyskeepawake','displaysleep','acwake','lidwake','sleep_on_power_button','autorestart','destroyfvkeyonstandby','powernap','haltlevel','haltafter','haltremain','lessbright','ring','dps','reduce','sleep_count','dark_wake_count','user_wake_count','wattage','backgroundtask','applepushservicetask','userisactive','preventuseridledisplaysleep','preventsystemsleep','externalmedia','preventuseridlesystemsleep','networkclientactive','cpu_scheduler_limit','cpu_available_cpus','cpu_speed_limit','ups_percent','timeremaining','instanttimetoempty','permanentfailurestatus','packreserve','avgtimetofull','instantamperage','amperage','designcyclecount','avgtimetoempty') as $item) {
            
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
                $sql = "CREATE TABLE %_temp (id INTEGER PRIMARY KEY, serial_number VARCHAR(255) UNIQUE, manufacture_date VARCHAR(255), design_capacity INTEGER, max_capacity INTEGER, max_percent INTEGER, current_capacity INTEGER, current_percent INTEGER, cycle_count INTEGER, temperature INTEGER, condition VARCHAR(255), timestamp INTEGER)";
                $this->exec(sprintf($sql, $this->tablename));

                $sql = "INSERT INTO %_temp 
							SELECT id, serial_number, manufacture_date, design_capacity, max_capacity, max_percent, current_capacity, current_percent, cycle_count, temperature, condition, timestamp
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
                foreach (array('hibernatefile','schedule','adapter_id','family_code','adapter_serial_number','combined_sys_load','user_sys_load','thermal_level','battery_level','ups_name','active_profile','ups_charging_status','standbydelay','standby','womp','halfdim','gpuswitch','sms','networkoversleep','disksleep','sleep','autopoweroffdelay','hibernatemode','autopoweroff','ttyskeepawake','displaysleep','acwake','lidwake','sleep_on_power_button','autorestart','destroyfvkeyonstandby','powernap','haltlevel','haltafter','haltremain','lessbright','ring','dps','reduce','sleep_count','dark_wake_count','user_wake_count','wattage','backgroundtask','applepushservicetask','userisactive','preventuseridledisplaysleep','preventsystemsleep','externalmedia','preventuseridlesystemsleep','networkclientactive','cpu_scheduler_limit','cpu_available_cpus','cpu_speed_limit','ups_percent','sleep_prevented_by','externalconnected','timeremaining','instanttimetoempty','cellvoltage','voltage','permanentfailurestatus','manufacturer','packreserve','avgtimetofull','batteryserialnumber','instantamperage','fullycharged','amperage','ischarging','designcyclecount','avgtimetoempty') as $item) {
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
