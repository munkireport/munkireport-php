<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Installhistory extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('installhistory', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->index();
            $table->dateTimeTz('date');
            $table->string('displayName');
            $table->string('displayVersion');
            $table->string('packageIdentifiers');
            $table->string('processName');

            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('installhistory');
    }
}
