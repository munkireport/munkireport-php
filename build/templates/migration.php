<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CLASSNAME extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('MODULE', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('example_string');
            $table->integer('example_integer');

            $table->index('example_string');
            $table->index('example_integer');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('MODULE');
    }
}
