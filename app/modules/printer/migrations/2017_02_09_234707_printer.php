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

            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('ppd')->nullable();
            $table->string('driver_version')->nullable();
            $table->string('url')->nullable();
            $table->string('default_set')->nullable();
            $table->string('printer_status')->nullable();
            $table->string('printer_sharing')->nullable();

            $table->index('default_set');
            $table->index('name');
            $table->index('ppd');
            $table->index('printer_sharing');
            $table->index('printer_status');
            $table->index('serial_number');
            $table->index('url');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('printer');
    }
}
