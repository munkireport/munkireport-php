<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DiskreportFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'diskreport';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('diskreport', function (Blueprint $table) {
                $table->index('serial_number', 'diskreport_serial_number');
                $table->index('VolumeType', 'diskreport_VolumeType');
                $table->index('MountPoint', 'diskreport_MountPoint');
                $table->index('VolumeName', 'diskreport_VolumeName');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'diskreport',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'diskreport_serial_number',
                        'diskreport_VolumeType',
                        'diskreport_MountPoint',
                        'diskreport_VolumeName'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}