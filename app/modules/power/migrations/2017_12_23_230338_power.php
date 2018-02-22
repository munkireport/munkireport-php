<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Power extends Migration
{
    private $tableName = 'power';
    private $tableNameV2 = 'power_orig';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }


        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('manufacture_date')->nullable();
            $table->integer('design_capacity')->default(0)->nullable();
            $table->integer('max_capacity')->default(0)->nullable();
            $table->integer('max_percent')->default(0)->nullable();
            $table->integer('current_capacity')->default(0)->nullable();
            $table->integer('current_percent')->default(0)->nullable();
            $table->integer('cycle_count')->default(0)->nullable();
            $table->integer('temperature')->default(0)->nullable();
            $table->string('condition')->nullable();
            
            $table->text('sleep_prevented_by')->nullable();
            $table->string('hibernatefile')->nullable();
            $table->text('schedule')->nullable();
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
            $table->float('voltage')->nullable();
            $table->integer('permanentfailurestatus')->nullable();
            $table->integer('packreserve')->nullable();
            $table->integer('avgtimetofull')->nullable();
            $table->float('amperage')->nullable();
            $table->integer('designcyclecount')->nullable();
            $table->integer('avgtimetoempty')->nullable();
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                manufacture_date,
                design_capacity,
                max_capacity,
                max_percent,
                current_capacity,
                current_percent,
                cycle_count,
                temperature,
                `condition`,
                sleep_prevented_by,
                hibernatefile,
                schedule,
                adapter_id,
                family_code,
                adapter_serial_number,
                combined_sys_load,
                user_sys_load,
                thermal_level,
                battery_level,
                ups_name,
                active_profile,
                ups_charging_status,
                externalconnected,
                cellvoltage,
                manufacturer,
                batteryserialnumber,
                fullycharged,
                ischarging,
                standbydelay,
                standby,
                womp,
                halfdim,
                gpuswitch,
                sms,
                networkoversleep,
                disksleep,
                sleep,
                autopoweroffdelay,
                hibernatemode,
                autopoweroff,
                ttyskeepawake,
                displaysleep,
                acwake,
                lidwake,
                sleep_on_power_button,
                autorestart,
                destroyfvkeyonstandby,
                powernap,
                haltlevel,
                haltafter,
                haltremain,
                lessbright,
                sleep_count,
                dark_wake_count,
                user_wake_count,
                wattage,
                backgroundtask,
                applepushservicetask,
                userisactive,
                preventuseridledisplaysleep,
                preventsystemsleep,
                externalmedia,
                preventuseridlesystemsleep,
                networkclientactive,
                cpu_scheduler_limit,
                cpu_available_cpus,
                cpu_speed_limit,
                ups_percent,
                timeremaining,
                instanttimetoempty,
                voltage,
                permanentfailurestatus,
                packreserve,
                avgtimetofull,
                amperage,
                designcyclecount,
                avgtimetoempty
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
            $table->index('manufacture_date');
            $table->index('design_capacity');
            $table->index('max_capacity');
            $table->index('max_percent');
            $table->index('current_capacity');
            $table->index('current_percent');
            $table->index('cycle_count');
            $table->index('temperature');
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
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
