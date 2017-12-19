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
            $table->string('runtype')->nullable();
            $table->string('version')->nullable();
            $table->integer('errors')->nullable();
            $table->integer('warnings')->nullable();
            $table->string('manifestname')->nullable();
            $table->longText('error_json')->nullable();
            $table->longText('warning_json')->nullable();
            $table->string('starttime')->nullable();
            $table->string('endtime')->nullable();
            $table->string('timestamp')->nullable();

            $table->index('errors');
            $table->index('manifestname');
            $table->index('runtype');
            $table->index('timestamp');
            $table->index('version');
            $table->index('warnings');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('munkireport');
    }
}
