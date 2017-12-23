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

        if ($capsule::schema()->hasTable('bluetooth')) {
            $capsule::schema()->rename('bluetooth', 'bluetooth_v2');
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
                bluetooth 
            SELECT 
                serial_number,
                battery_percent,
                device_type
            FROM
                bluetooth_v2');
        }
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('bluetooth');
        if ($capsule::schema()->hasTable('bluetooth_v2')) {
            $capsule::schema()->rename('bluetooth_v2', 'bluetooth');
        }
    }
}
