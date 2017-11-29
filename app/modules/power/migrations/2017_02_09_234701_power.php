<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Power extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('power', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('manufacture_date');
            $table->integer('design_capacity');
            $table->integer('max_capacity');
            $table->integer('max_percent');
            $table->integer('current_capacity');
            $table->integer('current_percent');
            $table->integer('cycle_count');
            $table->integer('temperature');
            $table->string('condition');
            $table->integer('timestamp');
            
            $table->text('sleep_prevented_by');
            $table->string('hibernatefile');
            $table->text('schedule');
            $table->string('adapter_id');
            // TODO: only partial

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('power');
    }
}
