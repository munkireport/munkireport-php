<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MachineGroup extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('machine_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('groupid')->nullable();
            $table->string('property');
            $table->string('value');
//            $table->timestamps();
            
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('machine_group');
    }
}
