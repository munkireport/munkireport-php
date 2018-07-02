<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class InventoryitemRenameSerialcolumn extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'inventoryitem';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('inventoryitem');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('inventoryitem', function (Blueprint $table) {
                $table->renameColumn('serial', 'serial_number');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('inventoryitem');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table('inventoryitem', function (Blueprint $table) {
                $table->renameColumn('serial_number', 'serial');
            });

            $this->markLegacyRollbackRan();
        }
    }
}