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

            $table->integer('type')->nullable();
            $table->string('display_serial')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('vendor')->nullable();
            $table->string('model')->nullable();
            $table->string('manufactured')->nullable();
            $table->string('native')->nullable();
            $table->integer('timestamp')->nullable();

//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('displays');
    }
}
