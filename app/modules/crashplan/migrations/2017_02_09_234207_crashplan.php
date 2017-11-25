<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Crashplan extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('crashplan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('destination');
            $table->integer('last_success');
            $table->integer('duration');
            $table->integer('last_failure');
            $table->string('reason');
            $table->integer('timestamp');
//            $table->timestamps();

            $table->index('reason', 'crashplan_reason');
            $table->index('serial_number', 'crashplan_serial_number');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('crashplan');
    }
}
