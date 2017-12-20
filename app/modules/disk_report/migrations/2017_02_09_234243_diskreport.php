<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Diskreport extends Migration
{
    public function up()
    {
        $capsule = new Capsule();

        $capsule::schema()->create('diskreport', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->bigInteger('TotalSize');
            $table->bigInteger('FreeSpace');
            $table->bigInteger('Percentage');
            $table->string('SMARTStatus');
            $table->string('VolumeType');
            $table->string('media_type');
            $table->string('BusProtocol');
            $table->integer('Internal');
            $table->string('MountPoint');
            $table->string('VolumeName');
            $table->integer('CoreStorageEncrypted');
            $table->bigInteger('timestamp');

            $table->index('serial_number');
            $table->index('MountPoint');
            $table->index('media_type');
            $table->index('VolumeName');
            $table->index('VolumeType');

//            $table->timestamps();
        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('diskreport');
    }
}
