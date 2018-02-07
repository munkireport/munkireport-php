<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Bluetooth extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable('bluetooth_orig')) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable('bluetooth')) {
            $capsule::schema()->rename('bluetooth', 'bluetooth_orig');
            $migrateData = true;
        }

        $capsule::schema()->create('bluetooth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->integer('battery_percent');
            $table->string('device_type');

            $table->index('serial_number');
            $table->index('battery_percent');
            $table->index('device_type');
        });

        if ($migrateData) {
            $capsule::select('INSERT INTO 
                bluetooth (serial_number, battery_percent, device_type) 
            SELECT 
                serial_number,
                battery_percent,
                device_type
            FROM
                bluetooth_orig');
        }
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('bluetooth');
        if ($capsule::schema()->hasTable('bluetooth_orig')) {
            $capsule::schema()->rename('bluetooth_orig', 'bluetooth');
        }
    }
}
