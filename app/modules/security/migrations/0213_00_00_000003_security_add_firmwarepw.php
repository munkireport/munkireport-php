<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddFirmwarepw extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 3;
    public static $legacyTableName = 'security';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('security');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('security', function (Blueprint $table) {
                $table->string('firmwarepw')->nullable();
                $table->index('firmwarepw', 'security_firmwarepw');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('security');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'security',
                function (Blueprint $table) {
                    $table->dropIndex('security_firmwarepw');
                    $table->dropColumn('firmwarepw');
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}