<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PowerFixNullableColumns extends Migration
{
    private $tableName = 'power';

    public function up()
    {
        $capsule = new Capsule();
    
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
                        
            $table->string('manufacture_date')->nullable()->change();
            $table->integer('design_capacity')->nullable()->change();
            $table->integer('max_capacity')->nullable()->change();
            $table->integer('max_percent')->nullable()->change();
            $table->integer('current_capacity')->nullable()->change();
            $table->integer('current_percent')->nullable()->change();
            $table->integer('cycle_count')->nullable()->change();
            $table->integer('temperature')->nullable()->change();
            $table->string('condition')->nullable()->change();
            $table->string('hibernatefile')->nullable()->change();
            $table->text('schedule')->nullable()->change();
            $table->string('adapter_id')->nullable()->change();
            $table->string('family_code')->nullable()->change();
            $table->string('adapter_serial_number')->nullable()->change();
            $table->string('combined_sys_load')->nullable()->change();
            $table->string('user_sys_load')->nullable()->change();
            $table->string('thermal_level')->nullable()->change();
            $table->string('battery_level')->nullable()->change();
            $table->string('ups_name')->nullable()->change();
            $table->string('active_profile')->nullable()->change();
            $table->string('ups_charging_status')->nullable()->change();
            $table->string('externalconnected')->nullable()->change();
            $table->string('cellvoltage')->nullable()->change();
            $table->string('manufacturer')->nullable()->change();
            $table->string('batteryserialnumber')->nullable()->change();
            $table->string('fullycharged')->nullable()->change();
            $table->string('ischarging')->nullable()->change();
        });
     }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('manufacture_date')->change();
            $table->integer('design_capacity')->change();
            $table->integer('max_capacity')->change();
            $table->integer('max_percent')->change();
            $table->integer('current_capacity')->change();
            $table->integer('current_percent')->change();
            $table->integer('cycle_count')->change();
            $table->integer('temperature')->change();
            $table->string('condition')->change();
            $table->string('hibernatefile')->change();
            $table->text('schedule')->change();
            $table->string('adapter_id')->change();
            $table->string('family_code')->change();
            $table->string('adapter_serial_number')->change();
            $table->string('combined_sys_load')->change();
            $table->string('user_sys_load')->change();
            $table->string('thermal_level')->change();
            $table->string('battery_level')->change();
            $table->string('ups_name')->change();
            $table->string('active_profile')->change();
            $table->string('ups_charging_status')->change();
            $table->string('externalconnected')->change();
            $table->string('cellvoltage')->change();
            $table->string('manufacturer')->change();
            $table->string('batteryserialnumber')->change();
            $table->string('fullycharged')->change();
            $table->string('ischarging')->change();            
        });
    }
}
