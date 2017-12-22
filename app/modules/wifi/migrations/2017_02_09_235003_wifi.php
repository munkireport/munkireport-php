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
            
            $table->string('serial_number')->unique();
            $table->integer('agrctlrssi');
            $table->integer('agrextrssi');
            $table->integer('agrctlnoise');
            $table->integer('agrextnoise');
            $table->string('state');
            $table->string('op_mode');
            $table->integer('lasttxrate');
            $table->string('lastassocstatus');
            $table->integer('maxrate');
            $table->string('x802_11_auth');
            $table->string('link_auth');
            $table->string('bssid');
            $table->string('ssid');
            $table->integer('mcs');
            $table->string('channel');

            $table->index('bssid');
            $table->index('ssid');
            $table->index('state');
        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('wifi');
    }
}
