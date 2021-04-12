<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MachineAddIndexes extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'machine';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('machine', function (Blueprint $table) {
                $table->index('hostname', 'machine_hostname');
                $table->index('machine_model', 'machine_machine_model');
                $table->index('machine_desc', 'machine_machine_desc');
                $table->index('current_processor_speed', 'machine_current_processor_speed');
                $table->index('cpu_arch', 'machine_cpu_arch');
                $table->index('os_version', 'machine_os_version');
                $table->index('physical_memory', 'machine_physical_memory');
                $table->index('platform_UUID', 'machine_platform_UUID');
                $table->index('number_processors', 'machine_number_processors');
                $table->index('SMC_version_system', 'machine_SMC_version_system');
                $table->index('boot_rom_version', 'machine_boot_rom_version');
                $table->index('bus_speed', 'machine_bus_speed');
                $table->index('computer_name', 'machine_computer_name');
                $table->index('l2_cache', 'machine_l2_cache');
                $table->index('machine_name', 'machine_machine_name');
                $table->index('packages', 'machine_packages');
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('machine');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'machine',
                function (Blueprint $table) {
                    $table->dropIndex([
                        'machine_hostname',
                        'machine_machine_model',
                        'machine_machine_desc',
                        'machine_current_processor_speed',
                        'machine_cpu_arch',
                        'machine_os_version',
                        'machine_physical_memory',
                        'machine_platform_UUID',
                        'machine_number_processors',
                        'machine_SMC_version_system',
                        'machine_boot_rom_version',
                        'machine_bus_speed',
                        'machine_computer_name',
                        'machine_l2_cache',
                        'machine_machine_name',
                        'machine_packages'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}