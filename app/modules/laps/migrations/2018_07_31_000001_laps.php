<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Laps extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('laps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('useraccount',128)->nullable();
            $table->text('password')->nullable();
            $table->bigInteger('dateset')->nullable();
            $table->bigInteger('dateexpires')->nullable();
           
            $table->index('useraccount');
            $table->index('dateset');
            $table->index('dateexpires');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('laps');
    }
}
