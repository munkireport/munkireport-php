<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class NetworkFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'network';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('network');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('network', function (Blueprint $table) {
                $table->index('serial_number', 'network_serial_number');
                $table->index(['serial_number', 'service'], 'network_serial_number_service');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('network');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'network',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'network_serial_number',
                        'network_serial_number_service'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}