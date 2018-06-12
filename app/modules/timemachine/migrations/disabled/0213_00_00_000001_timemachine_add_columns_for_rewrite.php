<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class TimemachineAddColumnsForRewrite extends Migration
{

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'timemachine';

    public function up()
    {
        $legacyVersion = $this->getLegacyModelSchemaVersion('timemachine');
        $capsule = new Capsule();

        if ($legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'timemachine', function (Blueprint $table) {
                    $table->string('consistency_scan_date')->nullable();
                    $table->string('date_of_latest_warning')->nullable();
                    $table->string('destination_id')->nullable();
                    $table->text('destination_uuids')->nullable();
                    $table->string('last_known_encryption_state')->nullable();
                    $table->string('result')->nullable();
                    $table->string('root_volume_uuid')->nullable();
                    $table->text('snapshot_dates')->nullable();
                    $table->text('exclude_by_path')->nullable();
                    $table->string('host_uuids')->nullable();
                    $table->string('last_configuration_trace_date')->nullable();
                    $table->string('last_destination_id')->nullable();
                    $table->string('localized_disk_image_volume_name')->nullable();
                    $table->text('skip_paths')->nullable();
                    $table->string('alias_volume_name')->nullable();
                    $table->string('earliest_snapshot_date')->nullable();
                    $table->string('latest_snapshot_date')->nullable();
                    $table->string('mount_point')->nullable();
                    $table->string('network_url')->nullable();
                    $table->string('server_display_name')->nullable();
                    $table->string('time_capsule_display_name')->nullable();
                    $table->string('volume_display_name')->nullable();
                    $table->bigInteger('bytes_available')->nullable();
                    $table->bigInteger('bytes_used')->nullable();

                    $table->index('consistency_scan_date', 'timemachine_consistency_scan_date');
                    $table->index('date_of_latest_warning', 'timemachine_date_of_latest_warning');
                    $table->index('destination_id', 'timemachine_destination_id');
                    $table->index('last_known_encryption_state', 'timemachine_last_known_encryption_state');
                    $table->index('result', 'timemachine_result');
                    $table->index('root_volume_uuid', 'timemachine_root_volume_uuid');
                    $table->index('host_uuids', 'timemachine_host_uuids');
                    $table->index('last_configuration_trace_date', 'timemachine_last_configuration_trace_date');
                    $table->index('last_destination_id', 'timemachine_last_destination_id');
                    $table->index('localized_disk_image_volume_name', 'timemachine_localized_disk_image_volume_name');
                    $table->index('alias_volume_name', 'timemachine_alias_volume_name');
                    $table->index('earliest_snapshot_date', 'timemachine_earliest_snapshot_date');
                    $table->index('latest_snapshot_date', 'timemachine_latest_snapshot_date');
                    $table->index('mount_point', 'timemachine_mount_point');
                    $table->index('network_url', 'timemachine_network_url');
                    $table->index('server_display_name', 'timemachine_server_display_name');
                    $table->index('time_capsule_display_name', 'timemachine_time_capsule_display_name');
                    $table->index('volume_display_name', 'timemachine_volume_display_name');
                    $table->index('bytes_available', 'timemachine_bytes_available');
                    $table->index('bytes_used', 'timemachine_bytes_used');
                });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('timemachine');

        if ($legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'timemachine',
                function (Blueprint $table) {
                    $table->dropColumn([
                        'consistency_scan_date',
                        'date_of_latest_warning',
                        'destination_id',
                        'destination_uuids',
                        'last_known_encryption_state',
                        'result',
                        'root_volume_uuid',
                        'snapshot_dates',
                        'exclude_by_path',
                        'host_uuids',
                        'last_configuration_trace_date',
                        'last_destination_id',
                        'localized_disk_image_volume_name',
                        'skip_paths',
                        'alias_volume_name',
                        'earliest_snapshot_date',
                        'latest_snapshot_date',
                        'mount_point',
                        'network_url',
                        'server_display_name',
                        'time_capsule_display_name',
                        'volume_display_name',
                        'bytes_available',
                        'bytes_used'
                    ]);

                    $table->dropIndex('timemachine_consistency_scan_date');
                    $table->dropIndex('timemachine_date_of_latest_warning');
                    $table->dropIndex('timemachine_destination_id');
                    $table->dropIndex('timemachine_last_known_encryption_state');
                    $table->dropIndex('timemachine_result');
                    $table->dropIndex('timemachine_root_volume_uuid');
                    $table->dropIndex('timemachine_host_uuids');
                    $table->dropIndex('timemachine_last_configuration_trace_date');
                    $table->dropIndex('timemachine_last_destination_id');
                    $table->dropIndex('timemachine_localized_disk_image_volume_name');
                    $table->dropIndex('timemachine_alias_volume_name');
                    $table->dropIndex('timemachine_earliest_snapshot_date');
                    $table->dropIndex('timemachine_latest_snapshot_date');
                    $table->dropIndex('timemachine_mount_point');
                    $table->dropIndex('timemachine_network_url');
                    $table->dropIndex('timemachine_server_display_name');
                    $table->dropIndex('timemachine_time_capsule_display_name');
                    $table->dropIndex('timemachine_volume_display_name');
                    $table->dropIndex('timemachine_bytes_available');
                    $table->dropIndex('timemachine_bytes_used');
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
    }