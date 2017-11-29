<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Location extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('location', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('address')->nullable();
            $table->integer('altitude')->default(0);
            $table->string('currentstatus');
            $table->boolean('ls_enabled')->default(false);
            $table->string('lastlocationrun');
            $table->string('lastrun');
            $table->double('latitude')->default(0.0);
            $table->integer('latitudeaccuracy')->default(0);
            $table->double('longitude')->default(0.0);
            $table->integer('longitudeaccuracy')->default(0);
            $table->string('stalelocation')->nullable();

            $table->index('address', 'location_address');
            $table->index('currentstatus', 'location_currentstatus');

            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('location');
    }
}
