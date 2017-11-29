<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Printer extends Migration
{
    public function up()
    {
        Schema::create('printer', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name');
            $table->string('ppd');
            $table->string('driver_version');
            $table->string('url');
            $table->string('default_set');
            $table->string('printer_status');
            $table->string('printer_sharing');

            $table->index(['serial_number', 'name']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('printer');
    }
}
