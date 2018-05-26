<?php
/**
 * MunkiReport Legacy Migration
 * Model Changes prior to migration system
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class TimemachineSchemaV1 extends Migration
{
    public function up()
    {
        $capsule = new Capsule();

        // Try to upgrade v0 tables from <= 2.13
        if ($capsule::schema()->hasTable('timemachine')) {
            $cols = $capsule::schema()->getColumnListing('timemachine');
            $capsule::schema()->table(
                'timemachine', function (Blueprint $table) use ($cols) {
                // Changes to timemachine_model.php, schema v1, v2.13
                !in_array('always_show_deleted_backups_warning', $cols) && $table->boolean('always_show_deleted_backups_warning')->nullable();
                !in_array('auto_backup', $cols) && $table->boolean('auto_backup')->nullable();
                !in_array('bytes_available', $cols) && $table->bigInteger('bytes_available')->nullable();
                !in_array('bytes_used', $cols) && $table->bigInteger('bytes_used')->nullable();
                !in_array('consistency_scan_date', $cols) && $table->string('consistency_scan_date')->nullable();
                !in_array('date_of_latest_warning', $cols) && $table->string('date_of_latest_warning')->nullable();
                !in_array('destination_id', $cols) && $table->string('destination_id')->nullable();
                !in_array('destination_uuids', $cols) && $table->text('destination_uuids')->nullable();
                !in_array('last_known_encryption_state', $cols) && $table->string('last_known_encryption_state')->nullable();
                !in_array('result', $cols) && $table->string('result')->nullable();
                !in_array('root_volume_uuid', $cols) && $table->string('root_volume_uuid')->nullable();
                !in_array('snapshot_dates', $cols) && $table->text('snapshot_dates')->nullable();
                !in_array('exclude_by_path', $cols) && $table->text('exclude_by_path')->nullable();
                !in_array('host_uuids', $cols) && $table->string('host_uuids')->nullable();
                !in_array('last_configuration_trace_date', $cols) && $table->string('last_configuration_trace_date')->nullable();
                !in_array('last_destination_id', $cols) && $table->string('last_destination_id')->nullable();
                !in_array('localized_disk_image_volume_name', $cols) && $table->string('localized_disk_image_volume_name')->nullable();
                !in_array('mobile_backups', $cols) && $table->boolean('mobile_backups')->nullable();
                !in_array('skip_paths', $cols) && $table->text('skip_paths')->nullable();
                !in_array('skip_system_files', $cols) && $table->boolean('skip_system_files')->nullable();
                !in_array('alias_volume_name', $cols) && $table->string('alias_volume_name')->nullable();
                !in_array('earliest_snapshot_date', $cols) && $table->string('earliest_snapshot_date')->nullable();
                !in_array('is_network_destination', $cols) && $table->boolean('is_network_destination')->nullable();
                !in_array('latest_snapshot_date', $cols) && $table->string('latest_snapshot_date')->nullable();
                !in_array('mount_point', $cols) && $table->string('mount_point')->nullable();
                !in_array('network_url', $cols) && $table->string('network_url')->nullable();
                !in_array('server_display_name', $cols) && $table->string('server_display_name')->nullable();
                !in_array('snapshot_count', $cols) && $table->integer('snapshot_count')->nullable();
                !in_array('time_capsule_display_name', $cols) && $table->string('time_capsule_display_name')->nullable();
                !in_array('volume_display_name', $cols) && $table->string('volume_display_name')->nullable();
                !in_array('destinations', $cols) && $table->integer('destinations')->nullable();
            });

        }
    }

    public function down() {


    }
}