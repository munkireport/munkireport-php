<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class AddColumnsForPmsetData extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'power';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('power', function (Blueprint $table) {
                $table->string('hibernatefile')->nullable();
                $table->string('schedule')->nullable();
                $table->string('adapter_id')->nullable();
                $table->string('family_code')->nullable();
                $table->string('adapter_serial_number')->nullable();
                $table->string('combined_sys_load')->nullable();
                $table->string('user_sys_load')->nullable();
                $table->string('thermal_level')->nullable();
                $table->string('battery_level')->nullable();
                $table->string('ups_name')->nullable();
                $table->string('active_profile')->nullable();
                $table->string('ups_charging_status')->nullable();
                $table->string('externalconnected')->nullable();
                $table->string('cellvoltage')->nullable();
                $table->string('manufacturer')->nullable();
                $table->string('batteryserialnumber')->nullable();
                $table->string('fullycharged')->nullable();
                $table->string('ischarging')->nullable();

                $table->float('voltage')->nullable();
                $table->float('amperage')->nullable();

                $table->text('sleep_prevented_by')->nullable();

                $table->integer('standbydelay')->nullable();
                $table->integer('standby')->nullable();
                $table->integer('womp')->nullable();
                $table->integer('halfdim')->nullable();
                $table->integer('gpuswitch')->nullable();
                $table->integer('sms')->nullable();
                $table->integer('networkoversleep')->nullable();
                $table->integer('disksleep')->nullable();
                $table->integer('sleep')->nullable();
                $table->integer('autopoweroffdelay')->nullable();
                $table->integer('hibernatemode')->nullable();
                $table->integer('autopoweroff')->nullable();
                $table->integer('ttyskeepawake')->nullable();
                $table->integer('displaysleep')->nullable();
                $table->integer('acwake')->nullable();
                $table->integer('lidwake')->nullable();
                $table->integer('sleep_on_power_button')->nullable();
                $table->integer('autorestart')->nullable();
                $table->integer('destroyfvkeyonstandby')->nullable();
                $table->integer('powernap')->nullable();
                $table->integer('haltlevel')->nullable();
                $table->integer('haltafter')->nullable();
                $table->integer('haltremain')->nullable();
                $table->integer('lessbright')->nullable();
                $table->integer('sleep_count')->nullable();
                $table->integer('dark_wake_count')->nullable();
                $table->integer('user_wake_count')->nullable();
                $table->integer('wattage')->nullable();
                $table->integer('backgroundtask')->nullable();
                $table->integer('applepushservicetask')->nullable();
                $table->integer('userisactive')->nullable();
                $table->integer('preventuseridledisplaysleep')->nullable();
                $table->integer('preventsystemsleep')->nullable();
                $table->integer('externalmedia')->nullable();
                $table->integer('preventuseridlesystemsleep')->nullable();
                $table->integer('networkclientactive')->nullable();
                $table->integer('cpu_scheduler_limit')->nullable();
                $table->integer('cpu_available_cpus')->nullable();
                $table->integer('cpu_speed_limit')->nullable();
                $table->integer('ups_percent')->nullable();
                $table->integer('timeremaining')->nullable();
                $table->integer('instanttimetoempty')->nullable();
                $table->integer('permanentfailurestatus')->nullable();
                $table->integer('packreserve')->nullable();
                $table->integer('avgtimetofull')->nullable();
                $table->integer('designcyclecount')->nullable();
                $table->integer('avgtimetoempty')->nullable();

                // TODO: indexes
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table('power', function (Blueprint $table) {
                $table->dropColumn([
                    'hibernatefile',
                    'schedule',
                    'adapter_id',
                    'family_code',
                    'adapter_serial_number',
                    'combined_sys_load',
                    'user_sys_load',
                    'thermal_level',
                    'battery_level',
                    'ups_name',
                    'active_profile',
                    'ups_charging_status',
                    'externalconnected',
                    'cellvoltage',
                    'manufacturer',
                    'batteryserialnumber',
                    'fullycharged',
                    'ischarging',
                    'voltage',
                    'amperage',
                    'sleep_prevented_by',
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
                ]);
            });

            $this->markLegacyRollbackRan();
        }
    }
}