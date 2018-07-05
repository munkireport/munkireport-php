<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 4;
    public static $legacyTableName = 'security';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('security');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('security', function (Blueprint $table) {
                //$table->index('serial_number', 'security_serial_number');
                //$table->index('gatekeeper', 'security_gatekeeper');
                //$table->index('sip', 'security_sip');
                // TODO: why is this a duplicate of migration 1
                //$table->index('ssh_users', 'security_ssh_users');
                //$table->index('ard_users', 'security_ard_users');
                //$table->index('firmwarepw', 'security_firmwarepw');
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
                    $table->dropIndex([
                        'security_serial_number',
                        'security_gatekeeper',
                        'security_sip',
                        'security_ssh_users',
                        'security_ard_users',
                        'security_firmwarepw'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}