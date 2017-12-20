<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Findmymac extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('findmymac', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('status');
            $table->string('ownerdisplayname')->nullable();
            $table->string('email')->nullable();
            $table->string('personid')->nullable();
            $table->string('hostname')->nullable();

            
            $table->index('serial_number');
            $table->index('status');
            $table->index('ownerdisplayname');
            $table->index('email');
            $table->index('personid');
            $table->index('hostname');
            
//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('findmymac');
    }
}
