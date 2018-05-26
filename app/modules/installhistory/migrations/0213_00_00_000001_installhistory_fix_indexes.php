<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Installhistory_Fix_Indexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'installhistory';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('installhistory');
        $capsule = new Capsule();

        if ($legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('installhistory', function (Blueprint $table) {
                $table->index('serial_number', 'installhistory_serial_number');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('installhistory');

        if ($legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'installhistory',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'installhistory_serial_number'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}