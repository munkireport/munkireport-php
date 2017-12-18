<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Munkiinfo extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('munkiinfo', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->nullable();
            $table->string('munkiinfo_key')->nullable();
            $table->string('munkiinfo_value')->nullable();

            $table->index('serial_number', 'munkiinfo_serial_number');

//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('munkiinfo');
    }
}
