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
            $table->string('thermal_pressure');
            $table->integer('backlight_max');
            $table->integer('backlight_min');
            $table->integer('backlight');
            $table->integer('keyboard_backlight');
            $table->float('ibyte_rate');
            $table->float('ibytes');
            $table->float('ipacket_rate');
            $table->float('ipackets');
            $table->float('obyte_rate');
            $table->float('obytes');
            $table->float('opacket_rate');
            $table->float('opackets');
            $table->float('rbytes_per_s');
            $table->float('rops_per_s');
            $table->float('wbytes_per_s');
            $table->float('wops_per_s');
            $table->float('rbytes_diff');
            $table->float('rops_diff');
            $table->float('wbytes_diff');
            $table->float('wops_diff');
            $table->float('package_watts');
            $table->float('package_joules');
            $table->float('freq_hz'); // CPU
            $table->float('freq_ratio'); // CPU
            $table->string('gpu_name');
            $table->float('gpu_freq_hz');
            $table->float('gpu_freq_mhz');
            $table->float('gpu_freq_ratio');
            $table->float('gpu_busy');
            
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
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('usage_stats');
    }
}
