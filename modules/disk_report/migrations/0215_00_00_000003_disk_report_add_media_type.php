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

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('diskreport', function (Blueprint $table) {
                $table->string('media_type')->default('-');
                $table->index('media_type', 'diskreport_media_type');
            });

            $capsule::table('diskreport')
                ->whereIn('volumeType', ['hdd', 'ssd', 'fusion', 'raid'])
                ->update(Array(
                    'media_type' => $capsule::raw('volumeType'),  // TODO: Not literally volumeType but the column named this.
                    'volumeType' => '-'
                ));

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
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