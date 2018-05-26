<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MachineAddBuildversion extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 5;
    public static $legacyTableName = 'machine';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('machine', function (Blueprint $table) {
                $table->string('buildversion');
                $table->index('buildversion', 'machine_buildversion');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'machine',
                function (Blueprint $table) {
                    $table->dropColumn('buildversion');
                    $table->dropIndex('machine_buildversion');
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}