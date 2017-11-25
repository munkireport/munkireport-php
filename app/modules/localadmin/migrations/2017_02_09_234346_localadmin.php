<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Localadmin extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('localadmin', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('users');

//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('localadmin');
    }
}
