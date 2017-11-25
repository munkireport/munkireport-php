<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class FilevaultStatus extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('filevault_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('filevault_status');
            $table->string('filevault_users');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('filevault_status');
    }
}
