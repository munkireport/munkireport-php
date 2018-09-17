<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Ibridge extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('ibridge', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('boot_uuid',128)->nullable();
            $table->string('build',128)->nullable();
            $table->string('model_identifier',128)->nullable();
            $table->string('model_name',128)->nullable();
            $table->string('ibridge_serial_number',128)->nullable();
            $table->string('marketing_name',128)->nullable();
           
            $table->index('boot_uuid');
            $table->index('build');
            $table->index('model_identifier');
            $table->index('model_name');
            $table->index('ibridge_serial_number');
            $table->index('marketing_name');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ibridge');
    }
}
