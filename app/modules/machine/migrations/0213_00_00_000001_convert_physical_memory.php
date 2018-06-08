<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ConvertPhysicalMemory extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'machine';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            // Chop "GB" suffix and change column to integer
            $capsule::table('machine')
                ->update(['physical_memory' => $capsule::raw("SUBSTRING(physical_memory, ' ', 1)")]);

            $capsule::schema()->table('machine', function (Blueprint $table) {
                $table->integer('physical_memory')->change();
            });

            $this->markLegacyMigrationRan();
        }
    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');
//
//        if ($legacyVersion == static::$legacySchemaVersion) {
//            $capsule::schema()->table('machine', function (Blueprint $table) {
//
//            });
//
//            $this->markLegacyRollbackRan();
//        }
//    }
}