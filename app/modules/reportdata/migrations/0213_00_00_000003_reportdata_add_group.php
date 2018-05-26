<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ReportdataAddGroup extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 3;
    public static $legacyTableName = 'reportdata';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('reportdata');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('reportdata', function (Blueprint $table) {
                $table->integer('machine_group')->default(0);
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('reportdata');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table('reportdata', function (Blueprint $table) {
                $table->dropColumn('machine_group');
            });

            $this->markLegacyRollbackRan();
        }
    }
}