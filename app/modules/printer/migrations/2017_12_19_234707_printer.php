<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Printer extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('printer', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name');
            $table->string('ppd');
            $table->string('driver_version');
            $table->string('url');
            $table->string('default_set');
            $table->string('printer_status');
            $table->string('printer_sharing');

            $table->index('serial_number');
            $table->index('name');
            $table->index('ppd');
            $table->index('driver_version');
            $table->index('url');
            $table->index('default_set');
            $table->index('printer_status');
            $table->index('printer_sharing');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('printer');
    }
}
