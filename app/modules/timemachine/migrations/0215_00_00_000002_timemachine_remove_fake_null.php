<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class TimemachineRemoveFakeNull extends Migration
{

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'timemachine';

    public function up()
    {
        $legacyVersion = $this->getLegacyModelSchemaVersion('timemachine');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $cols = array(
                'destinations',
                'snapshot_count',
                'is_network_destination',
                'skip_system_files',
                'mobile_backups',
                'bytes_used',
                'bytes_available',
                'auto_backup',
                'always_show_deleted_backups_warning',
                'duration'
            );

            foreach ($cols as $col) {
                $capsule::table('timemachine')
                    ->where($col, '=', -9876543)
                    ->orWhere($col, '=', -9876540)
                    ->update([$col => null]);
            }

            $this->markLegacyMigrationRan();
        }
    }

    public function down()
    {
        $legacyVersion = $this->getLegacyModelSchemaVersion('timemachine');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
//            $capsule::schema()->table(
//                'timemachine',
//                function (Blueprint $table) {
//
//                }
//            );

            $this->markLegacyRollbackRan();
        }
    }
}