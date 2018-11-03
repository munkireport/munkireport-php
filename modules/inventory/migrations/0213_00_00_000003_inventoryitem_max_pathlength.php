<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class InventoryitemMaxPathlength extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 3;
    public static $legacyTableName = 'inventoryitem';

//    public function up() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('inventoryitem');
//        $capsule = new Capsule();
//
//        if ($legacyVersion < static::$legacySchemaVersion) {
//            $capsule::schema()->table('inventoryitem', function (Blueprint $table) {
//
//            });
//
//            $this->markLegacyMigrationRan();
//        }
//    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('inventoryitem');
//
//        if ($legacyVersion == static::$legacySchemaVersion) {
//            $capsule::schema()->table('inventoryitem', function (Blueprint $table) {
//
//            });
//
//            $this->markLegacyRollbackRan();
//        }
//    }
}