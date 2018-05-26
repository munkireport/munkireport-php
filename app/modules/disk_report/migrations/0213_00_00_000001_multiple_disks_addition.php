<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DiskReport_multiple_disks_addition extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'diskreport';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');
        $capsule = new Capsule();

        if ($legacyVersion < static::$legacySchemaVersion) {


            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('diskreport');

        if ($legacyVersion == static::$legacySchemaVersion) {


            $this->markLegacyRollbackRan();
        }
    }
}