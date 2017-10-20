<?php

// Remove fake nulls and set them to NULL

class Migration_power_remove_fake_null extends \Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'power';
    }

    public function up()
    {
        // Set Nulls
         foreach (array('design_capacity','max_capacity','max_percent','current_capacity','current_percent','cycle_count','temperature','standbydelay','standby','womp','halfdim','gpuswitch','sms','networkoversleep','disksleep','sleep','autopoweroffdelay','hibernatemode','autopoweroff','ttyskeepawake','displaysleep','acwake','lidwake','sleep_on_power_button','autorestart','destroyfvkeyonstandby','powernap','haltlevel','haltafter','haltremain','lessbright','sleep_count','dark_wake_count','user_wake_count','wattage','backgroundtask','applepushservicetask','userisactive','preventuseridledisplaysleep','preventsystemsleep','externalmedia','preventuseridlesystemsleep','networkclientactive','cpu_scheduler_limit','cpu_available_cpus','cpu_speed_limit','ups_percent','timeremaining','instanttimetoempty','permanentfailurestatus','packreserve','avgtimetofull','designcyclecount','avgtimetoempty') as $item)
        {    
            $sql = 'UPDATE power 
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
