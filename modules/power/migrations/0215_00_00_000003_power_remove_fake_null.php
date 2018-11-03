<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PowerRemoveFakeNull extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 3;
    public static $legacyTableName = 'power';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');
        $capsule = new Capsule();

        $cols = array(
            'design_capacity',
            'max_capacity',
            'max_percent',
            'current_capacity',
            'current_percent',
            'cycle_count',
            'temperature',
            'standbydelay',
            'standby',
            'womp',
            'halfdim',
            'gpuswitch',
            'sms',
            'networkoversleep',
            'disksleep',
            'sleep',
            'autopoweroffdelay',
            'hibernatemode',
            'autopoweroff',
            'ttyskeepawake',
            'displaysleep',
            'acwake',
            'lidwake',
            'sleep_on_power_button',
            'autorestart',
            'destroyfvkeyonstandby',
            'powernap',
            'haltlevel',
            'haltafter',
            'haltremain',
            'lessbright',
            'sleep_count',
            'dark_wake_count',
            'user_wake_count',
            'wattage',
            'backgroundtask',
            'applepushservicetask',
            'userisactive',
            'preventuseridledisplaysleep',
            'preventsystemsleep',
            'externalmedia',
            'preventuseridlesystemsleep',
            'networkclientactive',
            'cpu_scheduler_limit',
            'cpu_available_cpus',
            'cpu_speed_limit',
            'ups_percent',
            'timeremaining',
            'instanttimetoempty',
            'permanentfailurestatus',
            'packreserve',
            'avgtimetofull',
            'designcyclecount',
            'avgtimetoempty'
        );

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            foreach ($cols as $col) {
                $capsule::table('power')
                    ->where($col, '=', -9876543)
                    ->orWhere($col, '=', -9876540)
                    ->update([$col => null]);
            }

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {


            $this->markLegacyRollbackRan();
        }
    }
}