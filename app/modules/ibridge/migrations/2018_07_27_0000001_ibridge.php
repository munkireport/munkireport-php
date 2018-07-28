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
            $table->string('ibridge_boot_uuid',128)->nullable();
            $table->string('ibridge_build',128)->nullable();
            $table->string('ibridge_model_identifier',128)->nullable();
            $table->string('ibridge_model_name',128)->nullable();
            $table->string('ibridge_serial_number',128)->nullable();
            $table->string('ibridge_marketing_name',128)->nullable();
           
            $table->index('ibridge_boot_uuid');
            $table->index('ibridge_build');
            $table->index('ibridge_model_identifier');
            $table->index('ibridge_model_name');
            $table->index('ibridge_serial_number');
            $table->index('ibridge_marketing_name');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ibridge');
    }
}
