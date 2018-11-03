<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class UsageStats extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('usage_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->bigInteger('timestamp');
            $table->string('thermal_pressure')->nullable();
            $table->integer('backlight_max')->nullable();
            $table->integer('backlight_min')->nullable();
            $table->integer('backlight')->nullable();
            $table->integer('keyboard_backlight')->nullable();
            $table->double('ibyte_rate')->nullable();
            $table->bigInteger('ibytes')->nullable();
            $table->double('ipacket_rate')->nullable();
            $table->bigInteger('ipackets')->nullable();
            $table->double('obyte_rate')->nullable();
            $table->bigInteger('obytes')->nullable();
            $table->double('opacket_rate')->nullable();
            $table->bigInteger('opackets')->nullable();
            $table->double('rbytes_per_s')->nullable();
            $table->double('rops_per_s')->nullable();
            $table->double('wbytes_per_s')->nullable();
            $table->double('wops_per_s')->nullable();
            $table->bigInteger('rbytes_diff')->nullable();
            $table->bigInteger('rops_diff')->nullable();
            $table->bigInteger('wbytes_diff')->nullable();
            $table->bigInteger('wops_diff')->nullable();
            $table->double('package_watts')->nullable();
            $table->double('package_joules')->nullable();
            $table->bigInteger('freq_hz')->nullable(); // CPU
            $table->double('freq_ratio')->nullable(); // CPU
            $table->string('gpu_name')->nullable();
            $table->bigInteger('gpu_freq_hz')->nullable();
            $table->double('gpu_freq_mhz')->nullable();
            $table->double('gpu_freq_ratio')->nullable();
            $table->double('gpu_busy')->nullable();
            $table->string('kern_bootargs')->nullable();
            
            $table->index('backlight_max');
            $table->index('backlight_min');
            $table->index('backlight');
            $table->index('keyboard_backlight');
            $table->index('ibyte_rate');
            $table->index('ibytes');
            $table->index('ipacket_rate');
            $table->index('ipackets');
            $table->index('obyte_rate');
            $table->index('obytes');
            $table->index('opacket_rate');
            $table->index('opackets');
            $table->index('rbytes_per_s');
            $table->index('rops_per_s');
            $table->index('wbytes_per_s');
            $table->index('wops_per_s');
            $table->index('rbytes_diff');
            $table->index('rops_diff');
            $table->index('wbytes_diff');
            $table->index('wops_diff');
            $table->index('thermal_pressure');
            $table->index('package_watts');
            $table->index('package_joules');
            $table->index('freq_hz');
            $table->index('freq_ratio');
            $table->index('gpu_name');
            $table->index('gpu_freq_hz');
            $table->index('gpu_freq_mhz');
            $table->index('gpu_freq_ratio');
            $table->index('gpu_busy');
            $table->index('kern_bootargs');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('usage_stats');
    }
}
