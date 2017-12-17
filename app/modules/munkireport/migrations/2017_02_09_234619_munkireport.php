<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Munkireport extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('munkireport', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('runtype');
            $table->string('version');
            $table->integer('errors');
            $table->integer('warnings');
            $table->string('manifestname')->nullable();
            $table->longText('error_json')->nullable();
            $table->longText('warning_json')->nullable();
            $table->string('starttime');
            $table->string('endtime');
            $table->string('timestamp');

            $table->index('errors','munkireport_errors');
            $table->index('manifestname','munkireport_manifestname');
            $table->index('runtype','munkireport_runtype');
            $table->index('timestamp','munkireport_timestamp');
            $table->index('version','munkireport_version');
            $table->index('warnings','munkireport_warnings');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('munkireport');
    }
}
