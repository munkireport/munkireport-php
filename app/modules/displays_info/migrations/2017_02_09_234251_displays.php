<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Displays extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('displays', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('type');
            $table->string('display_serial');
            $table->string('serial_number');
            $table->string('vendor');
            $table->string('model');
            $table->string('manufactured');
            $table->string('native');
            $table->integer('timestamp');

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('displays');
    }
}
