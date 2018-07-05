<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MunkireportFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'munkireport';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('munkireport');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('munkireport', function (Blueprint $table) {

                $table->index('timestamp', 'munkireport_timestamp');
                $table->index('runtype', 'munkireport_runtype');
                $table->index('version', 'munkireport_version');
                $table->index('errors', 'munkireport_errors');
                $table->index('warnings', 'munkireport_warnings');
                $table->index('manifestname', 'munkireport_manifestname');
                $table->index('managedinstalls', 'munkireport_managedinstalls');
                $table->index('pendinginstalls', 'munkireport_pendinginstalls');
                $table->index('installresults', 'munkireport_installresults');
                $table->index('removalresults', 'munkireport_removalresults');
                $table->index('failedinstalls', 'munkireport_failedinstalls');
                $table->index('pendingremovals', 'munkireport_pendingremovals');
                $table->index('itemstoinstall', 'munkireport_itemstoinstall');
                $table->index('appleupdates', 'munkireport_appleupdates');

            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('munkireport');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'munkireport',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'munkireport_timestamp',
                        'munkireport_runtype',
                        'munkireport_version',
                        'munkireport_errors',
                        'munkireport_warnings',
                        'munkireport_manifestname',
                        'munkireport_managedinstalls',
                        'munkireport_pendinginstalls',
                        'munkireport_installresults',
                        'munkireport_removalresults',
                        'munkireport_failedinstalls',
                        'munkireport_pendingremovals',
                        'munkireport_itemstoinstall',
                        'munkireport_appleupdates'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}