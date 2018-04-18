<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Supportedos extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('supported_os', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->integer('current_os')->nullable();
            $table->integer('highest_supported')->nullable();
            $table->string('machine_id')->nullable();
            $table->bigInteger('last_touch')->nullable();

            $table->index('current_os');
            $table->index('highest_supported');
            $table->index('machine_id');
            $table->index('last_touch');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('supported_os');
    }
}
