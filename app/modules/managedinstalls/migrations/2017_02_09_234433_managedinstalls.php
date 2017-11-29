<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Managedinstalls extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('managedinstalls', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name');
            $table->string('display_name');
            $table->string('version')->nullable();
            $table->integer('size')->nullable();
            $table->integer('installed');
            $table->string('status');
            $table->string('type');

            $table->index('display_name', 'managedinstalls_display_name');
            $table->index('name', 'managedinstalls_name');
            $table->index(['name', 'version'], 'managedinstalls_name_version');
            $table->index('serial_number', 'managedinstalls_serial_number');
            $table->index('status', 'managedinstalls_status');
            $table->index('type', 'managedinstalls_type');
            $table->index('version', 'managedinstalls_version');
//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('managedinstalls');
    }
}
