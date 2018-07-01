<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PowerFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'power';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('power', function (Blueprint $table) {
                $table->index('manufacture_date', 'power_manufacture_date');
                $table->index('design_capacity', 'power_design_capacity');
                $table->index('max_capacity', 'power_max_capacity');
                $table->index('max_percent', 'power_max_percent');
                $table->index('current_capacity', 'power_current_capacity');
                $table->index('current_percent', 'power_current_percent');
                $table->index('cycle_count', 'power_cycle_count');
                $table->index('temperature', 'power_temperature');
                $table->index('timestamp', 'power_timestamp');
                $table->index('hibernatefile', 'power_hibernatefile');
                $table->index('active_profile', 'power_active_profile');
                $table->index('standbydelay', 'power_standbydelay');
                $table->index('standby', 'power_standby');
                $table->index('womp', 'power_womp');
                $table->index('halfdim', 'power_halfdim');
                $table->index('gpuswitch', 'power_gpuswitch');
                $table->index('sms', 'power_sms');
                $table->index('networkoversleep', 'power_networkoversleep');
                $table->index('disksleep', 'power_disksleep');
                $table->index('sleep', 'power_sleep');
                $table->index('autopoweroffdelay', 'power_autopoweroffdelay');
                $table->index('hibernatemode', 'power_hibernatemode');
                $table->index('autopoweroff', 'power_autopoweroff');
                $table->index('ttyskeepawake', 'power_ttyskeepawake');
                $table->index('displaysleep', 'power_displaysleep');
                $table->index('acwake', 'power_acwake');
                $table->index('lidwake', 'power_lidwake');
                $table->index('sleep_on_power_button', 'power_sleep_on_power_button');
                $table->index('autorestart', 'power_autorestart');
                $table->index('destroyfvkeyonstandby', 'power_destroyfvkeyonstandby');
                $table->index('powernap', 'power_powernap');
                $table->index('sleep_count', 'power_sleep_count');
                $table->index('dark_wake_count', 'power_dark_wake_count');
                $table->index('user_wake_count', 'power_user_wake_count');
                $table->index('wattage', 'power_wattage');
                $table->index('backgroundtask', 'power_backgroundtask');
                $table->index('applepushservicetask', 'power_applepushservicetask');
                $table->index('userisactive', 'power_userisactive');
                $table->index('preventuseridledisplaysleep', 'power_preventuseridledisplaysleep');
                $table->index('preventsystemsleep', 'power_preventsystemsleep');
                $table->index('externalmedia', 'power_externalmedia');
                $table->index('preventuseridlesystemsleep', 'power_preventuseridlesystemsleep');
                $table->index('networkclientactive', 'power_networkclientactive');
                $table->index('externalconnected', 'power_externalconnected');
                $table->index('timeremaining', 'power_timeremaining');
                $table->index('instanttimetoempty', 'power_instanttimetoempty');
                $table->index('cellvoltage', 'power_cellvoltage');
                $table->index('voltage', 'power_voltage');
                $table->index('permanentfailurestatus', 'power_permanentfailurestatus');
                $table->index('manufacturer', 'power_manufacturer');
                $table->index('packreserve', 'power_packreserve');
                $table->index('avgtimetofull', 'power_avgtimetofull');
                $table->index('batteryserialnumber', 'power_batteryserialnumber');
                $table->index('amperage', 'power_amperage');
                $table->index('fullycharged', 'power_fullycharged');
                $table->index('ischarging', 'power_ischarging');
                $table->index('designcyclecount', 'power_designcyclecount');
                $table->index('avgtimetoempty', 'power_avgtimetoempty');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');

        if ($legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'power',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'power_manufacture_date',
                        'power_design_capacity',
                        'power_max_capacity',
                        'power_max_percent',
                        'power_current_capacity',
                        'power_current_percent',
                        'power_cycle_count',
                        'power_temperature',
                        'power_timestamp',
                        'power_hibernatefile',
                        'power_active_profile',
                        'power_standbydelay',
                        'power_standby',
                        'power_womp',
                        'power_halfdim',
                        'power_gpuswitch',
                        'power_sms',
                        'power_networkoversleep',
                        'power_disksleep',
                        'power_sleep',
                        'power_autopoweroffdelay',
                        'power_hibernatemode',
                        'power_autopoweroff',
                        'power_ttyskeepawake',
                        'power_displaysleep',
                        'power_acwake',
                        'power_lidwake',
                        'power_sleep_on_power_button',
                        'power_autorestart',
                        'power_destroyfvkeyonstandby',
                        'power_powernap',
                        'power_sleep_count',
                        'power_dark_wake_count',
                        'power_user_wake_count',
                        'power_wattage',
                        'power_backgroundtask',
                        'power_applepushservicetask',
                        'power_userisactive',
                        'power_preventuseridledisplaysleep',
                        'power_preventsystemsleep',
                        'power_externalmedia',
                        'power_preventuseridlesystemsleep',
                        'power_networkclientactive',
                        'power_externalconnected',
                        'power_timeremaining',
                        'power_instanttimetoempty',
                        'power_cellvoltage',
                        'power_voltage',
                        'power_permanentfailurestatus',
                        'power_manufacturer',
                        'power_packreserve',
                        'power_avgtimetofull',
                        'power_batteryserialnumber',
                        'power_amperage',
                        'power_fullycharged',
                        'power_ischarging',
                        'power_designcyclecount',
                        'power_avgtimetoempty'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}