<?php

// Sets the schedule colum to be TEXT

class Migration_power_schedule_text extends Model
{

    private $fields = array('manufacture_date','design_capacity','max_capacity','max_percent','current_capacity','current_percent','cycle_count','temperature','timestamp','hibernatefile','active_profile','standbydelay','standby','womp','halfdim','gpuswitch','sms','networkoversleep','disksleep','sleep','autopoweroffdelay','hibernatemode','autopoweroff','ttyskeepawake','displaysleep','acwake','lidwake','sleep_on_power_button','autorestart','destroyfvkeyonstandby','powernap','sleep_count','dark_wake_count','user_wake_count','wattage','backgroundtask','applepushservicetask','userisactive','preventuseridledisplaysleep','preventsystemsleep','externalmedia','preventuseridlesystemsleep','networkclientactive','externalconnected','timeremaining','instanttimetoempty','cellvoltage','voltage','permanentfailurestatus','manufacturer','packreserve','avgtimetofull','batteryserialnumber','amperage','fullycharged','ischarging','designcyclecount','avgtimetoempty');
    
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'power';
    }

    public function up()
    {
        switch ($this->get_driver())
        {
            // Set column type
            case 'sqlite':
                     
                    // Get database handle
                    $dbh = $this->getdbh();
                
                    // Wrap in transaction
                    $dbh->beginTransaction();

                    // Rename table
                    $sql = 'ALTER TABLE power RENAME TO power_tmp';
                    $this->exec($sql);
                
                    // Make new table
                    $sql = 'CREATE TABLE power(id VARCHAR(255), serial_number VARCHAR(255) UNIQUE, manufacture_date VARCHAR(255), design_capacity INT, max_capacity INT, max_percent INT, current_capacity INT, current_percent INT, cycle_count INT, temperature INT, condition VARCHAR(255), timestamp INT, sleep_prevented_by TEXT, hibernatefile VARCHAR(255), schedule TEXT, adapter_id VARCHAR(255), family_code VARCHAR(255), adapter_serial_number VARCHAR(255), combined_sys_load VARCHAR(255), user_sys_load VARCHAR(255), thermal_level VARCHAR(255), battery_level VARCHAR(255), ups_name VARCHAR(255), active_profile VARCHAR(255), ups_charging_status VARCHAR(255), externalconnected VARCHAR(255), cellvoltage VARCHAR(255), manufacturer VARCHAR(255), batteryserialnumber VARCHAR(255), fullycharged VARCHAR(255), ischarging VARCHAR(255), standbydelay INT, standby INT, womp INT, halfdim INT, gpuswitch INT, sms INT, networkoversleep INT, disksleep INT, sleep INT, autopoweroffdelay INT, hibernatemode INT, autopoweroff INT, ttyskeepawake INT, displaysleep INT, acwake INT, lidwake INT, sleep_on_power_button INT, autorestart INT, destroyfvkeyonstandby INT, powernap INT, haltlevel INT, haltafter INT, haltremain INT, lessbright INT, sleep_count INT, dark_wake_count INT, user_wake_count INT, wattage INT, backgroundtask INT, applepushservicetask INT, userisactive INT, preventuseridledisplaysleep INT, preventsystemsleep INT, externalmedia INT, preventuseridlesystemsleep INT, networkclientactive INT, cpu_scheduler_limit INT, cpu_available_cpus INT, cpu_speed_limit INT, ups_percent INT, timeremaining INT, instanttimetoempty INT, voltage FLOAT, permanentfailurestatus INT, packreserve INT, avgtimetofull INT, amperage FLOAT, designcyclecount INT, avgtimetoempty INT)';
                    $this->exec($sql);

                    // Copy over data from old table to new table
                    $sql = 'INSERT INTO power(id, serial_number, manufacture_date, design_capacity, max_capacity, max_percent, current_capacity, current_percent, cycle_count, temperature, condition, timestamp, sleep_prevented_by, hibernatefile, schedule, adapter_id, family_code, adapter_serial_number, combined_sys_load, user_sys_load, thermal_level, battery_level, ups_name, active_profile, ups_charging_status, externalconnected, cellvoltage, manufacturer, batteryserialnumber, fullycharged, ischarging, standbydelay, standby, womp, halfdim, gpuswitch, sms, networkoversleep, disksleep, sleep, autopoweroffdelay, hibernatemode, autopoweroff, ttyskeepawake, displaysleep, acwake, lidwake, sleep_on_power_button, autorestart, destroyfvkeyonstandby, powernap, haltlevel, haltafter, haltremain, lessbright, sleep_count, dark_wake_count, user_wake_count, wattage, backgroundtask, applepushservicetask, userisactive, preventuseridledisplaysleep, preventsystemsleep, externalmedia, preventuseridlesystemsleep, networkclientactive, cpu_scheduler_limit, cpu_available_cpus, cpu_speed_limit, ups_percent, timeremaining, instanttimetoempty, voltage, permanentfailurestatus, packreserve, avgtimetofull, amperage, designcyclecount, avgtimetoempty) SELECT id, serial_number, manufacture_date, design_capacity, max_capacity, max_percent, current_capacity, current_percent, cycle_count, temperature, condition, timestamp, sleep_prevented_by, hibernatefile, schedule, adapter_id, family_code, adapter_serial_number, combined_sys_load, user_sys_load, thermal_level, battery_level, ups_name, active_profile, ups_charging_status, externalconnected, cellvoltage, manufacturer, batteryserialnumber, fullycharged, ischarging, standbydelay, standby, womp, halfdim, gpuswitch, sms, networkoversleep, disksleep, sleep, autopoweroffdelay, hibernatemode, autopoweroff, ttyskeepawake, displaysleep, acwake, lidwake, sleep_on_power_button, autorestart, destroyfvkeyonstandby, powernap, haltlevel, haltafter, haltremain, lessbright, sleep_count, dark_wake_count, user_wake_count, wattage, backgroundtask, applepushservicetask, userisactive, preventuseridledisplaysleep, preventsystemsleep, externalmedia, preventuseridlesystemsleep, networkclientactive, cpu_scheduler_limit, cpu_available_cpus, cpu_speed_limit, ups_percent, timeremaining, instanttimetoempty, voltage, permanentfailurestatus, packreserve, avgtimetofull, amperage, designcyclecount, avgtimetoempty FROM power_tmp';
                    $this->exec($sql);
                
                    // Delete old table
                    $sql = 'DROP TABLE power_tmp';
                    $this->exec($sql);

                    foreach ($this->fields as $field) {
                        $sql = "CREATE INDEX power_$field ON power ($field)";
                        $this->exec($sql);
                    }

                    // Commit changes
                    $dbh->commit();
                
                break;

            case 'mysql':
                
                    $sql = 'ALTER TABLE power MODIFY schedule TEXT';
                    $this->exec($sql);
                
                break;

            default:
                throw new Exception("Unknown Datbase driver", 1);
        }
    }

    public function down()
    {
        throw new Exception("Can't go back", 1);
    }
}
