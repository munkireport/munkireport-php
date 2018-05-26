<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class AddFilevaultUsers extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'filevault_status';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('filevault_status');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('filevault_status', function (Blueprint $table) {
                $table->string('filevault_users')->nullable();
                $table->index('filevault_users', 'filevault_status_filevault_users');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('filevault_status');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'directoryservice',
                function (Blueprint $table) {
                    $table->dropColumn('filevault_users');
                    $table->dropIndex('filevault_status_filevault_users');
            });
            $this->markLegacyRollbackRan();
        }
    }
}