<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Network extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('network', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->nullable();
            $table->string('service')->nullable();
            $table->integer('order')->nullable();
            $table->integer('status')->nullable();
            $table->string('ethernet')->nullable();
            $table->string('clientid')->nullable();
            $table->string('ipv4conf')->nullable();
            $table->string('ipv4ip')->nullable();
            $table->string('ipv4mask')->nullable();
            $table->string('ipv4router')->nullable();
            $table->string('ipv6conf')->nullable();
            $table->string('ipv6ip')->nullable();
            $table->integer('ipv6prefixlen')->nullable();
            $table->string('ipv6router')->nullable();
            $table->bigInteger('timestamp')->nullable();

            $table->index('serial_number');
            $table->index(['serial_number', 'service']);

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('network');
    }
}
