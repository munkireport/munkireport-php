<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Hash extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('hash', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('name');
            $table->string('hash');
            $table->bigInteger('timestamp');

            $table->index(['serial_number']);
            $table->index(['serial_number', 'name']);

            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('hash');
    }
}
