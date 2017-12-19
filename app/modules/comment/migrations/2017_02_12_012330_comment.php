<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Comment extends Migration
{

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('section');
            $table->string('user');
            $table->text('text');
            $table->text('html');
            $table->bigInteger('timestamp');
//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('comment');
    }
}
