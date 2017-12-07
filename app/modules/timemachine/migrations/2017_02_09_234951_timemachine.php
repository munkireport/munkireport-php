<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Timemachine extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('timemachine', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('last_success');
            $table->string('last_failure');
            $table->string('last_failure_msg');
            $table->integer('duration');
            $table->string('timestamp');

            $table->integer('always_show_deleted_backups_warning');
            $table->integer('auto_backup');
            $table->bigInteger('bytes_available');
            $table->bigInteger('bytes_used');
            $table->string('consistency_scan_date');
            $table->string('date_of_latest_warning');
            $table->string('destination_id');
            $table->text('destination_uuids');
            $table->string('last_known_encryption_state');
            $table->string('result');
            $table->string('root_volume_uuid');
            $table->text('snapshot_dates');
            $table->text('exclude_by_path');
            $table->string('host_uuids');
            $table->string('last_configuration_trace_date');
            $table->string('last_destination_id');
            $table->string('localized_disk_image_volume_name');
            $table->integer('mobile_backups');
            $table->text('skip_paths');
            $table->integer('skip_system_files');
            $table->string('alias_volume_name');
            $table->string('earliest_snapshot_date');
            $table->integer('is_network_destination');
            $table->string('latest_snapshot_date');
            $table->string('mount_point');
            $table->string('network_url');
            $table->string('server_display_name');
            $table->integer('snapshot_count');
            $table->string('time_capsule_display_name');
            $table->string('volume_display_name');
            $table->integer('destinations');

            $table->index('alias_volume_name', 'timemachine_alias_volume_name');
            $table->index('always_show_deleted_backups_warning', 'timemachine_always_show_deleted_backups_warning');
            $table->index('auto_backup', 'timemachine_auto_backup');
            $table->index('bytes_available', 'timemachine_bytes_available');
            $table->index('bytes_used', 'timemachine_bytes_used');
            $table->index('consistency_scan_date', 'timemachine_consistency_scan_date');
            $table->index('date_of_latest_warning', 'timemachine_date_of_latest_warning');
            $table->index('destination_id', 'timemachine_destination_id');
            $table->index('destinations', 'timemachine_destinations');
            $table->index('duration', 'timemachine_duration');
            $table->index('earliest_snapshot_date', 'timemachine_earliest_snapshot_date');
            $table->index('host_uuids', 'timemachine_host_uuids');
            $table->index('is_network_destination', 'timemachine_is_network_destination');
            $table->index('last_configuration_trace_date', 'timemachine_last_configuration_trace_date');
            $table->index('last_destination_id', 'timemachine_last_destination_id');
            $table->index('last_failure', 'timemachine_last_failure');
            $table->index('last_failure_msg', 'timemachine_last_failure_msg');
            $table->index('last_known_encryption_state', 'timemachine_last_known_encryption_state');
            $table->index('last_success', 'timemachine_last_success');
            $table->index('latest_snapshot_date', 'timemachine_latest_snapshot_date');
            $table->index('localized_disk_image_volume_name', 'timemachine_localized_disk_image_volume_name');
            $table->index('mobile_backups', 'timemachine_mobile_backups');
            $table->index('mount_point', 'timemachine_mount_point');
            $table->index('network_url', 'timemachine_network_url');
            $table->index('result', 'timemachine_result');
            $table->index('root_volume_uuid', 'timemachine_root_volume_uuid');
            $table->index('server_display_name', 'timemachine_server_display_name');
            $table->index('skip_system_files', 'timemachine_skip_system_files');
            $table->index('snapshot_count', 'timemachine_snapshot_count');
            $table->index('time_capsule_display_name', 'timemachine_time_capsule_display_name');
            $table->index('timestamp', 'timemachine_timestamp');
            $table->index('volume_display_name', 'timemachine_volume_display_name');
//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('timemachine');
    }
}
