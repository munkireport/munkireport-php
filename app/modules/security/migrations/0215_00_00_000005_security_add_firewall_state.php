<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddFirewallState extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 5;
    public static $legacyTableName = 'security';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('security');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('security', function (Blueprint $table) {
                $table->integer('firewall_state')->nullable();
                $table->index('firewall_state', 'security_firewall_state');
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
                    $table->dropIndex('security_firewall_state');
                    $table->dropColumn('firewall_state');
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}
