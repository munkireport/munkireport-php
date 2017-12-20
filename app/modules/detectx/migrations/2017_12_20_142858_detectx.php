<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Detectx extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('detectx', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->bigInteger('searchdate');
            $table->integer('numberofissues');
            $table->string('status');
            $table->integer('scantime');
            $table->boolean('spotlightindexing');
            $table->text('issues');

            $table->index('numberofissues');
            $table->index('searchdate');
            $table->index('status');
            $table->index('scantime');
            $table->index('spotlightindexing');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('detectx');
    }
}
