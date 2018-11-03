<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DirectoryServiceFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'directoryservice';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('directoryservice');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('directoryservice', function (Blueprint $table) {
                $table->index('which_directory_service', 'directoryservice_which_directory_service');
                $table->index('directory_service_comments', 'directoryservice_directory_service_comments');
                $table->index('allowedadmingroups', 'directoryservice_allowedadmingroups');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('directoryservice');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'directoryservice',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'directoryservice_which_directory_service',
                        'directoryservice_directory_service_comments',
                        'directoryservice_allowedadmingroups'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}