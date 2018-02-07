<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class UsageStats extends Migration
{
    private $tableName = 'usage_stats';

    public function up()
    {
        $capsule = new Capsule();
    
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
                        
            $table->double('ibyte_rate')->nullable()->change();
            $table->bigInteger('ibytes')->nullable()->change();
            $table->double('ipacket_rate')->nullable()->change();
            $table->bigInteger('ipackets')->nullable()->change();
            $table->double('obyte_rate')->nullable()->change();
            $table->bigInteger('obytes')->nullable()->change();
            $table->double('opacket_rate')->nullable()->change();
            $table->bigInteger('opackets')->nullable()->change();
            $table->double('rbytes_per_s')->nullable()->change();
            $table->double('rops_per_s')->nullable()->change();
            $table->double('wbytes_per_s')->nullable()->change();
            $table->double('wops_per_s')->nullable()->change();
            $table->bigInteger('rbytes_diff')->nullable()->change();
            $table->bigInteger('rops_diff')->nullable()->change();
            $table->bigInteger('wbytes_diff')->nullable()->change();
            $table->bigInteger('wops_diff')->nullable()->change();
            $table->double('package_watts')->nullable()->change();
            $table->double('package_joules')->nullable()->change();
            $table->bigInteger('freq_hz')->nullable()->change(); // CPU
            $table->double('freq_ratio')->nullable()->change(); // CPU
            $table->bigInteger('gpu_freq_hz')->nullable()->change();
            $table->double('gpu_freq_mhz')->nullable()->change();
            $table->double('gpu_freq_ratio')->nullable()->change();
            $table->double('gpu_busy')->nullable()->change();
         
        });
     }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->float('ibyte_rate')->nullable()->change();
            $table->float('ibytes')->nullable()->change();
            $table->float('ipacket_rate')->nullable()->change();
            $table->float('ipackets')->nullable()->change();
            $table->float('obyte_rate')->nullable()->change();
            $table->float('obytes')->nullable()->change();
            $table->float('opacket_rate')->nullable()->change();
            $table->float('opackets')->nullable()->change();
            $table->float('rbytes_per_s')->nullable()->change();
            $table->float('rops_per_s')->nullable()->change();
            $table->float('wbytes_per_s')->nullable()->change();
            $table->float('wops_per_s')->nullable()->change();
            $table->float('rbytes_diff')->nullable()->change();
            $table->float('rops_diff')->nullable()->change();
            $table->float('wbytes_diff')->nullable()->change();
            $table->float('wops_diff')->nullable()->change();
            $table->float('package_watts')->nullable()->change();
            $table->float('package_joules')->nullable()->change();
            $table->float('freq_hz')->nullable()->change(); // CPU
            $table->float('freq_ratio')->nullable()->change(); // CPU
            $table->float('gpu_freq_hz')->nullable()->change();
            $table->float('gpu_freq_mhz')->nullable()->change();
            $table->float('gpu_freq_ratio')->nullable()->change();
            $table->float('gpu_busy')->nullable()->change();
           
        });
    }
}
