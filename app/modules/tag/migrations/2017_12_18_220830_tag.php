<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Tag extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('tag', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('tag');
            $table->string('user');
            $table->bigInteger('timestamp');

            $table->index('tag');
            $table->index('user');
            $table->index('timestamp');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('tag');
    }
}
