<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class FirmwareEscrow extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('firmware_escrow', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->dateTime('enabled_date')->nullable();
            $table->string('firmware_password')->nullable();
            $table->string('firmware_mode')->nullable();

        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('firmware_escrow');
    }
}
