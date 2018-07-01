<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PowerScheduleText extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 4;
    public static $legacyTableName = 'power';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('power', function(Blueprint $table) {
                $table->text('schedule')->change();
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('power');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table('power', function(Blueprint $table) {
                $table->string('schedule')->change();
            });

            $this->markLegacyRollbackRan();
        }
    }
}