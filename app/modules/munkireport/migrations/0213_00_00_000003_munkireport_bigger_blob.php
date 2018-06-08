<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MunkireportBiggerBlob extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 3;
    public static $legacyTableName = 'munkireport';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('munkireport');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('munkireport', function (Blueprint $table) {
                //$table->binary('report_plist')->change();
            });

            $this->markLegacyMigrationRan();
        }
    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('munkireport');
//
//        if ($legacyVersion == static::$legacySchemaVersion) {
//            $capsule::schema()->table('munkireport', function (Blueprint $table) {
//
//            });
//
//            $this->markLegacyRollbackRan();
//        }
//    }
}