<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DiskReportAddMediaType extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 3;
    public static $legacyTableName = 'diskreport';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');
        $capsule = new Capsule();

        if ($legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('diskreport', function (Blueprint $table) {
                $table->string('media_type')->default('-');
                $table->index('media_type', 'diskreport_media_type');
            });

            // TODO:
            /*        $sql = "UPDATE diskreport
                SET media_type = volumeType, volumeType = '-'
                WHERE volumeType IN ('hdd', 'ssd', 'fusion', 'raid')";*/

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');

        if ($legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'diskreport',
                function (Blueprint $table) {
                    $table->dropIndex('diskreport_media_type');
                    $table->dropColumn('media_type');
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}