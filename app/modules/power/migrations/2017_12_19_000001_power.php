<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Power extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('power', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('manufacture_date');
            $table->integer('design_capacity')->default(0);
            $table->integer('max_capacity')->default(0);
            $table->integer('max_percent')->default(0);
            $table->integer('current_capacity')->default(0);
            $table->integer('current_percent')->default(0);
            $table->integer('cycle_count')->default(0);
            $table->integer('temperature')->default(0);
            $table->string('condition');
            $table->bigInteger('timestamp')->default(0);
            
            $table->text('sleep_prevented_by');
            $table->string('hibernatefile');
            $table->text('schedule');
            $table->string('adapter_id');
            $table->string('family_code');
            $table->string('adapter_serial_number');
            $table->string('combined_sys_load');
            $table->string('user_sys_load');
            $table->string('thermal_level');
            $table->string('battery_level');
            $table->string('ups_name');
            $table->string('active_profile');
            $table->string('ups_charging_status');
            $table->string('externalconnected');
            $table->string('cellvoltage');
            $table->string('manufacturer');
            $table->string('batteryserialnumber');
            $table->string('fullycharged');
            $table->string('ischarging');
            $table->integer('standbydelay');
            $table->integer('standby');
            $table->integer('womp');
            $table->integer('halfdim');
            $table->integer('gpuswitch');
            $table->integer('sms');
            $table->integer('networkoversleep');
            $table->integer('disksleep');
            $table->integer('sleep');
            $table->integer('autopoweroffdelay');
            $table->integer('hibernatemode');
            $table->integer('autopoweroff');
            $table->integer('ttyskeepawake');
            $table->integer('displaysleep');
            $table->integer('acwake');
            $table->integer('lidwake');
            $table->integer('sleep_on_power_button');
            $table->integer('autorestart');
            $table->integer('destroyfvkeyonstandby');
            $table->integer('powernap');
            $table->integer('haltlevel');
            $table->integer('haltafter');
            $table->integer('haltremain');
            $table->integer('lessbright');
            $table->integer('sleep_count');
            $table->integer('dark_wake_count');
            $table->integer('user_wake_count');
            $table->integer('wattage');
            $table->integer('backgroundtask');
            $table->integer('applepushservicetask');
            $table->integer('userisactive');
            $table->integer('preventuseridledisplaysleep');
            $table->integer('preventsystemsleep');
            $table->integer('externalmedia');
            $table->integer('preventuseridlesystemsleep');
            $table->integer('networkclientactive');
            $table->integer('cpu_scheduler_limit');
            $table->integer('cpu_available_cpus');
            $table->integer('cpu_speed_limit');
            $table->integer('ups_percent');
            $table->integer('timeremaining');
            $table->integer('instanttimetoempty');
            $table->float('voltage');
            $table->integer('permanentfailurestatus');
            $table->integer('packreserve');
            $table->integer('avgtimetofull');
            $table->float('amperage');
            $table->integer('designcyclecount');
            $table->integer('avgtimetoempty');

            $table->index('manufacture_date');
            $table->index('design_capacity');
            $table->index('max_capacity');
            $table->index('max_percent');
            $table->index('current_capacity');
            $table->index('current_percent');
            $table->index('cycle_count');
            $table->index('temperature');
            $table->index('timestamp');
            $table->index('hibernatefile');
            $table->index('active_profile');
            $table->index('standbydelay');
            $table->index('standby');
            $table->index('womp');
            $table->index('halfdim');
            $table->index('gpuswitch');
            $table->index('sms');
            $table->index('networkoversleep');
            $table->index('disksleep');
            $table->index('sleep');
            $table->index('autopoweroffdelay');
            $table->index('hibernatemode');
            $table->index('autopoweroff');
            $table->index('ttyskeepawake');
            $table->index('displaysleep');
            $table->index('acwake');
            $table->index('lidwake');
            $table->index('sleep_on_power_button');
            $table->index('autorestart');
            $table->index('destroyfvkeyonstandby');
            $table->index('powernap');
            $table->index('sleep_count');
            $table->index('dark_wake_count');
            $table->index('user_wake_count');
            $table->index('wattage');
            $table->index('backgroundtask');
            $table->index('applepushservicetask');
            $table->index('userisactive');
            $table->index('preventuseridledisplaysleep');
            $table->index('preventsystemsleep');
            $table->index('externalmedia');
            $table->index('preventuseridlesystemsleep');
            $table->index('networkclientactive');
            $table->index('externalconnected');
            $table->index('timeremaining');
            $table->index('instanttimetoempty');
            $table->index('cellvoltage');
            $table->index('voltage');
            $table->index('permanentfailurestatus');
            $table->index('manufacturer');
            $table->index('packreserve');
            $table->index('avgtimetofull');
            $table->index('batteryserialnumber');
            $table->index('amperage');
            $table->index('fullycharged');
            $table->index('ischarging');
            $table->index('designcyclecount');
            $table->index('avgtimetoempty');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('power');
    }
}
