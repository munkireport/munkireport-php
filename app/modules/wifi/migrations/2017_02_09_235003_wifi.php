<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Wifi extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('wifi', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('serial_number')->unique()->nullable();
            $table->integer('agrctlrssi')->nullable();
            $table->integer('agrextrssi')->nullable();
            $table->integer('agrctlnoise')->nullable();
            $table->integer('agrextnoise')->nullable();
            $table->string('state')->nullable();
            $table->string('op_mode')->nullable();
            $table->integer('lasttxrate')->nullable();
            $table->string('lastassocstatus')->nullable();
            $table->integer('maxrate')->nullable();
            $table->string('x802_11_auth')->nullable();
            $table->string('link_auth')->nullable();
            $table->string('bssid')->nullable();
            $table->string('ssid')->nullable();
            $table->integer('mcs')->nullable();
            $table->string('channel')->nullable();

//            $table->timestamps();

            $table->index('bssid', 'wifi_bssid');
            $table->index('ssid', 'wifi_ssid');
            $table->index('state', 'wifi_state');
        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('wifi');
    }
}
