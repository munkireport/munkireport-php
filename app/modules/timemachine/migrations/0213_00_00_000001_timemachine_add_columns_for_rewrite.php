<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Timemachine_Add_Columns_For_Rewrite extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'timemachine';

//    public function up() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('timemachine');
//        $capsule = new Capsule();
//
//        if ($legacyVersion < static::$legacySchemaVersion) {
//            $capsule::schema()->table('timemachine', function (Blueprint $table) {
//
//            });
//
//            $this->markLegacyMigrationRan();
//        }
//    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('timemachine');
//
//        if ($legacyVersion == static::$legacySchemaVersion) {
//            $capsule::schema()->table(
//                'timemachine',
//                function (Blueprint $table) {
//
//                }
//            );
//
//            $this->markLegacyRollbackRan();
//        }
//    }
}