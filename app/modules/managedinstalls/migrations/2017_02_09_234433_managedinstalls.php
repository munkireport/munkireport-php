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

            $table->index('display_name');
            $table->index('name');
            $table->index(['name', 'version']);
            $table->index('serial_number');
            $table->index('status');
            $table->index('type');
            $table->index('version');
//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('managedinstalls');
    }
}
