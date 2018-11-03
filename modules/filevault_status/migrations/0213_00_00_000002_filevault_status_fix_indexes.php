<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class FilevaultStatusFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'filevault_status';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('filevault_status');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('filevault_status', function (Blueprint $table) {
                $table->index('filevault_status', 'filevault_status_filevault_status');
                $table->index('filevault_users', 'filevault_status_filevault_users');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('filevault_status');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'filevault_status',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'filevault_status_filevault_status',
                        'filevault_status_filevault_users'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}