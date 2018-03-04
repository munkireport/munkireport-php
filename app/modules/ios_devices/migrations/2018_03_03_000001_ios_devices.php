<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Iosdevices extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('ios_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('serial')->nullable();
            $table->string('prefpath')->nullable();
            $table->string('build_version')->nullable();
            $table->bigInteger('connected')->nullable();
            $table->string('device_class')->nullable();
            $table->integer('family_id')->nullable();
            $table->integer('firmware_version')->nullable();
            $table->string('firmware_version_string')->nullable();
            $table->integer('software_version')->nullable();
            $table->string('ios_id')->nullable();
            $table->string('product_type')->nullable();
            $table->string('region_info')->nullable();
            $table->integer('use_count')->nullable();
            $table->string('imei')->nullable();
            $table->string('meid')->nullable();

            $table->index('serial_number');
            $table->index('serial');
            $table->index('prefpath');
            $table->index('build_version');
            $table->index('connected');
            $table->index('device_class');
            $table->index('family_id');
            $table->index('firmware_version');
            $table->index('firmware_version_string');
            $table->index('software_version');
            $table->index('ios_id');
            $table->index('product_type');
            $table->index('region_info');
            $table->index('use_count');
            $table->index('imei');
            $table->index('meid');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ios_devices');
    }
}
