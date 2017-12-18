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

            $table->index('device_type', 'bluetooth_device_type');
            $table->index('serial_number', 'bluetooth_serial_number')->nullable();

            $table->unique('serial_number', 'bluetooth_serial_number_unique');
//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('bluetooth');
    }
}
