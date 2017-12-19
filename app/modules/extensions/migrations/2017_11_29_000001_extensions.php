<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Extensions extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('extensions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name');
            $table->string('bundle_id');
            $table->string('version');
            $table->string('path');
            $table->string('codesign');
            $table->string('executable');

            $table->index('serial_number');
            $table->index('name');
            $table->index('bundle_id');
            $table->index('version');
            $table->index('path');
            $table->index('codesign');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('extensions');
    }
}
