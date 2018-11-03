<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class LocationStaleLocationColumns extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'location';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('location');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('location', function (Blueprint $table) {
                $table->string('stalelocation');
                $table->string('lastlocationrun');

                $table->index('stalelocation', 'location_stalelocation');
                $table->index('lastlocationrun', 'location_lastlocationrun');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('location');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'location',
                function (Blueprint $table) {
                    $table->dropColumn([
                        'stalelocation',
                        'lastlocationrun'
                    ]);

                    $table->dropIndex([
                        'location_stalelocation',
                        'location_lastlocationrun'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}