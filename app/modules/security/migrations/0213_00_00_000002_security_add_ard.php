<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddArd extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'security';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('security');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('security', function (Blueprint $table) {
                $table->string('ard_users')->nullable();
                $table->index('ard_users', 'security_ard_users');
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
                    $table->dropIndex('security_ard_users');
                    $table->dropColumn('ard_users');
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}