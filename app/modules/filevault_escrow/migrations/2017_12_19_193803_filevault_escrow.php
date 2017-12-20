<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class FilevaultEscrow extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('filevault_escrow', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('EnabledDate');
            $table->string('EnabledUser');
            $table->string('LVGUUID');
            $table->string('LVUUID');
            $table->string('PVUUID');
            $table->text('RecoveryKey');
            $table->string('HddSerial');

            $table->index('EnabledDate');
            $table->index('EnabledUser');
            $table->index('LVGUUID');
            $table->index('LVUUID');
            $table->index('PVUUID');
            $table->index('HddSerial');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('filevault_escrow');
    }
}
