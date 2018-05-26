<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * If you have MunkiReport v2 installed and it hasn't yet run the php migrations, these migrations will run as a part
 * of the v3 upgrade.
 *
 * This migration represents the time machine rewrite PR #650
 */
class TimemachineRewrite extends Migration
{
    public function up() {
        $capsule = new Capsule();
        if (!$capsule::schema()->hasColumn('timemachine', 'always_show_deleted_backups_warning')) {
            $capsule::schema()->table('timemachine', function (Blueprint $table) {
                $table->integer('always_show_deleted_backups_warning')->nullable();
                $table->integer('auto_backup')->nullable();
                $table->bigInteger('bytes_available')->nullable();
                $table->bigInteger('bytes_used')->nullable();
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
                $table->integer('mobile_backups')->nullable();
                $table->text('skip_paths')->nullable();
                $table->integer('skip_system_files')->nullable();
                $table->string('alias_volume_name')->nullable();
                $table->string('earliest_snapshot_date')->nullable();
                $table->integer('is_network_destination')->nullable();
                $table->string('latest_snapshot_date')->nullable();
                $table->string('mount_point')->nullable();
                $table->string('network_url')->nullable();
                $table->string('server_display_name')->nullable();
                $table->integer('snapshot_count')->nullable();
                $table->string('time_capsule_display_name')->nullable();
                $table->string('volume_display_name')->nullable();
                $table->integer('destinations')->nullable();

                $table->index('alias_volume_name');
                $table->index('always_show_deleted_backups_warning');
                $table->index('auto_backup');
                $table->index('bytes_available');
                $table->index('bytes_used');
                $table->index('consistency_scan_date');
                $table->index('date_of_latest_warning');
                $table->index('destination_id');
                $table->index('destinations');
                $table->index('duration');
                $table->index('earliest_snapshot_date');
                $table->index('host_uuids');
                $table->index('is_network_destination');
                $table->index('last_configuration_trace_date');
                $table->index('last_destination_id');
                $table->index('last_failure');
                $table->index('last_failure_msg');
                $table->index('last_known_encryption_state');
                $table->index('last_success');
                $table->index('latest_snapshot_date');
                $table->index('localized_disk_image_volume_name');
                $table->index('mobile_backups');
                $table->index('mount_point');
                $table->index('network_url');
                $table->index('result');
                $table->index('root_volume_uuid');
                $table->index('server_display_name');
                $table->index('skip_system_files');
                $table->index('snapshot_count');
                $table->index('time_capsule_display_name');
                $table->index('volume_display_name');
            });
        }
    }

    public function down() {
        $capsule = new Capsule();
//        $capsule::schema()->table('timemachine', function (Blueprint $table) {
//           $table->dropColumn('');
//        });
    }
}