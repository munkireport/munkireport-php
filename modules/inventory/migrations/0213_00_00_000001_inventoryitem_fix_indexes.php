<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class InventoryitemFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'inventoryitem';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('inventoryitem');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('inventoryitem', function (Blueprint $table) {
                $table->index('serial', 'inventoryitem_serial');
                $table->index(['name', 'version'], 'inventoryitem_name_version');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('inventoryitem');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'inventoryitem',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'inventoryitem_serial',
                        'inventoryitem_name_version'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}