<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CleanupHashTable extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'hash';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('hash');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $rename_list = array(
                'InstallHistory' => 'installhistory',
                'Machine' => 'machine',
                'InventoryItem' => 'inventory',
                'inventoryitem' => 'inventory',
                'Munkireport' => 'munkireport',
                'Reportdata' => 'reportdata',
                'filevault_status_model' => 'filevault_status',
                'localadmin_model' => 'localadmin',
                'network_model' => 'network',
                'disk_report_model' => 'disk_report'
            );

            foreach ($rename_list as $from => $to) {
                $capsule::table('hash')
                    ->where('name', '=', $from)
                    ->update(Array('name' => $to));
            }

            $this->markLegacyMigrationRan();
        }
    }

//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('hash');
//
//        if ($legacyVersion == static::$legacySchemaVersion) {
//
//
//            $this->markLegacyRollbackRan();
//        }
//    }
}