<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class WifiFixNullableColumns extends Migration
{
    private $tableName = 'wifi';

    public function up()
    {
        $capsule = new Capsule();
    
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
                        
            $table->integer('agrctlrssi')->nullable()->change();
            $table->integer('agrextrssi')->nullable()->change();
            $table->integer('agrctlnoise')->nullable()->change();
            $table->integer('agrextnoise')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('op_mode')->nullable()->change();
            $table->integer('lasttxrate')->nullable()->change();
            $table->string('lastassocstatus')->nullable()->change();
            $table->integer('maxrate')->nullable()->change();
            $table->string('x802_11_auth')->nullable()->change();
            $table->string('link_auth')->nullable()->change();
            $table->string('bssid')->nullable()->change();
            $table->string('ssid')->nullable()->change();
            $table->integer('mcs')->nullable()->change();
            $table->string('channel')->nullable()->change();
         
        });
     }
    
    public function down()
    {

    }
}
