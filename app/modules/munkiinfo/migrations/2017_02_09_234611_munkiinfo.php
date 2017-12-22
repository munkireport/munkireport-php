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

            $table->string('serial_number');
            $table->string('munkiinfo_key');
            $table->string('munkiinfo_value');

            $table->index('serial_number');
            $table->index('munkiinfo_key');
            $table->index('munkiinfo_value');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('munkiinfo');
    }
}
