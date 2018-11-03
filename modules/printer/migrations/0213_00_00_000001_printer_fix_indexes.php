<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PrinterFixIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'printer';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('printer');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('printer', function (Blueprint $table) {
                $table->index('serial_number', 'printer_serial_number');
                $table->index('name', 'printer_name');
                $table->index('ppd', 'printer_ppd');
                $table->index('url', 'printer_url');
                $table->index('default_set', 'printer_default_set');
                $table->index('printer_status', 'printer_printer_status');
                $table->index('printer_sharing', 'printer_printer_sharing');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('printer');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'printer',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'printer_serial_number',
                        'printer_name',
                        'printer_ppd',
                        'printer_url',
                        'printer_default_set',
                        'printer_printer_status',
                        'printer_printer_sharing'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}