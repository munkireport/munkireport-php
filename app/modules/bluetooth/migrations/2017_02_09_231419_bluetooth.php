<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Bluetooth extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('bluetooth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->integer('battery_percent')->nullable();
            $table->string('device_type')->nullable();

            $table->index('serial_number');
            $table->index('battery_percent');
            $table->index('device_type');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('bluetooth');
    }
}
