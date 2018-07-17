<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MachineOsversionInteger extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 4;
    public static $legacyTableName = 'machine';

//    public function up() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');
//        $capsule = new Capsule();
//
//        if ($legacyVersion < static::$legacySchemaVersion) {
//
//            $this->markLegacyMigrationRan();
//        }
//    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');
//
//        if ($legacyVersion == static::$legacySchemaVersion) {
//
//            $this->markLegacyRollbackRan();
//        }
//    }
}