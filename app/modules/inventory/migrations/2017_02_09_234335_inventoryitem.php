<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Inventoryitem extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('inventoryitem', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name');
            $table->string('version');
            $table->string('bundleid');
            $table->string('bundlename');
            $table->text('path');

            $table->index(['name', 'version']);
            $table->index('serial_number');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('inventoryitem');
    }
}
