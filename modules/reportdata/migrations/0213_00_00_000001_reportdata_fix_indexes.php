<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ReportdataFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'reportdata';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('reportdata');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('reportdata', function (Blueprint $table) {
                $table->index('console_user', 'reportdata_console_user');
                $table->index('long_username', 'reportdata_long_username');
                $table->index('remote_ip', 'reportdata_remote_ip');
                $table->index('reg_timestamp', 'reportdata_reg_timestamp');
                $table->index('timestamp', 'reportdata_timestamp');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('reportdata');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table('reportdata', function (Blueprint $table) {
                $table->dropIndex('reportdata_console_user');
                $table->dropIndex('reportdata_long_username');
                $table->dropIndex('reportdata_remote_ip');
                $table->dropIndex('reportdata_reg_timestamp');
                $table->dropIndex('reportdata_timestamp');
            });

            $this->markLegacyRollbackRan();
        }
    }
}