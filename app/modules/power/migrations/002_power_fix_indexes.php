<?php

// Remove indexes with non-standard name and re-add them with the proper name

class Migration_power_fix_indexes extends Model
{

    public function __construct()
    {
        parent::__construct('id', 'power'); //primary key, tablename

        $this->idx[] = array('manufacture_date');
        $this->idx[] = array('design_capacity');
        $this->idx[] = array('max_capacity');
        $this->idx[] = array('max_percent');
        $this->idx[] = array('current_capacity');
        $this->idx[] = array('current_percent');
        $this->idx[] = array('cycle_count');
        $this->idx[] = array('temperature');
        $this->idx[] = array('timestamp');
        $this->idx[] = array('hibernatefile');
        $this->idx[] = array('active_profile');
        $this->idx[] = array('standbydelay');
        $this->idx[] = array('standby');
        $this->idx[] = array('womp');
        $this->idx[] = array('halfdim');
        $this->idx[] = array('gpuswitch');
        $this->idx[] = array('sms');
        $this->idx[] = array('networkoversleep');
        $this->idx[] = array('disksleep');
        $this->idx[] = array('sleep');
        $this->idx[] = array('autopoweroffdelay');
        $this->idx[] = array('hibernatemode');
        $this->idx[] = array('autopoweroff');
        $this->idx[] = array('ttyskeepawake');
        $this->idx[] = array('displaysleep');
        $this->idx[] = array('acwake');
        $this->idx[] = array('lidwake');
        $this->idx[] = array('sleep_on_power_button');
        $this->idx[] = array('autorestart');
        $this->idx[] = array('destroyfvkeyonstandby');
        $this->idx[] = array('powernap');
        $this->idx[] = array('sleep_count');
        $this->idx[] = array('dark_wake_count');
        $this->idx[] = array('user_wake_count');
        $this->idx[] = array('wattage');
        $this->idx[] = array('backgroundtask');
        $this->idx[] = array('applepushservicetask');
        $this->idx[] = array('userisactive');
        $this->idx[] = array('preventuseridledisplaysleep');
        $this->idx[] = array('preventsystemsleep');
        $this->idx[] = array('externalmedia');
        $this->idx[] = array('preventuseridlesystemsleep');
        $this->idx[] = array('networkclientactive');
        $this->idx[] = array('externalconnected');
        $this->idx[] = array('timeremaining');
        $this->idx[] = array('instanttimetoempty');
        $this->idx[] = array('cellvoltage');
        $this->idx[] = array('voltage');
        $this->idx[] = array('permanentfailurestatus');
        $this->idx[] = array('manufacturer');
        $this->idx[] = array('packreserve');
        $this->idx[] = array('avgtimetofull');
        $this->idx[] = array('batteryserialnumber');
        $this->idx[] = array('amperage');
        $this->idx[] = array('fullycharged');
        $this->idx[] = array('ischarging');
        $this->idx[] = array('designcyclecount');
        $this->idx[] = array('avgtimetoempty');

    }

    public function up()
    {
        // Drop all indexes
        switch ($this->get_driver())
        {
            case 'sqlite':
                foreach($this->idx as $indexArray)
                {
                    $sql = 'DROP INDEX ' . $indexArray[0];
                    $this->exec($sql);
                }
                break;

            case 'mysql':
                foreach($this->idx as $indexArray)
                {
                    $sql = 'DROP INDEX ' . $indexArray[0] . 'ON power';
                    $this->exec($sql);
                }
                break;

            default:
                throw new Exception("Unknown Datbase driver", 1);
        }

        // Add all indexes
        $this->set_indexes();

    }

    public function down()
    {
        throw new Exception("Can't go back", 1);

    }
}
