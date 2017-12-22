<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Service extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->string('service_name');
            $table->string('service_state');
            $table->bigInteger('timestamp');
            
            $table->index('serial_number');
            $table->index('service_name');
            $table->index('service_state');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('service');
    }
}
