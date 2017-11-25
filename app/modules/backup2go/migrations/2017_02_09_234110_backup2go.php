<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Backup2go extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('backup2go', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('backupdate');

            $table->index('serial_number', 'backup2go_serial_number');
            // $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('backup2go');
    }
}
