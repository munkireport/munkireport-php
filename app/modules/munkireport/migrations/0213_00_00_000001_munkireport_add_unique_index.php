<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Munkireport_Add_Unique_Index extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'munkireport';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('munkireport');
        $capsule = new Capsule();

        if ($legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('munkireport', function (Blueprint $table) {
                $table->unique('serial_number', 'serial_number');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('munkireport');

        if ($legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table('munkireport', function (Blueprint $table) {
                $table->dropUnique('serial_number');
            });

            $this->markLegacyRollbackRan();
        }
    }
}