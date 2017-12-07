<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Diskreport extends Migration
{
    public function up()
    {
        $capsule = new Capsule();

        $legacy_migration_version = $capsule::table('migration')
            ->where('table_name', '=', 'diskreport')
            ->first();

        if ($legacy_migration_version && $legacy_migration_version->version === 3) {
            // No need to run this migration
        } else {
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
                $table->string('timestamp');

                $table->index('serial_number', 'diskreport_serial_number');
                $table->index('MountPoint', 'diskreport_MountPoint');
                $table->index('media_type', 'diskreport_media_type');
                $table->index('VolumeName', 'diskreport_VolumeName');
                $table->index('VolumeType', 'diskreport_VolumeType');

//            $table->timestamps();
            });
        }
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('diskreport');
    }
}
