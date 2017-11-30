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

            $table->index('default_set', 'printer_default_set');
            $table->index('name', 'printer_name');
            $table->index('ppd', 'printer_ppd');
            $table->index('printer_sharing', 'printer_printer_sharing');
            $table->index('printer_status', 'printer_printer_status');
            $table->index('serial_number', 'printer_serial_number');
            $table->index('url', 'printer_url');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('printer');
    }
}
