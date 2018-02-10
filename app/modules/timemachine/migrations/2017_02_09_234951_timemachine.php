<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Timemachine extends Migration
{
    private $tableName = 'timemachine';
    private $tableNameV2 = 'timemachine_orig';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique()->nullable();
            $table->string('last_success')->nullable();
            $table->string('last_failure')->nullable();
            $table->string('last_failure_msg')->nullable();
            $table->integer('duration')->nullable();

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

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                last_success,
                last_failure,
                last_failure_msg,
                duration,
                always_show_deleted_backups_warning,
                auto_backup,
                bytes_available,
                bytes_used,
                consistency_scan_date,
                date_of_latest_warning,
                destination_id,
                destination_uuids,
                last_known_encryption_state,
                result,
                root_volume_uuid,
                snapshot_dates,
                exclude_by_path,
                host_uuids,
                last_configuration_trace_date,
                last_destination_id,
                localized_disk_image_volume_name,
                mobile_backups,
                skip_paths,
                skip_system_files,
                alias_volume_name,
                earliest_snapshot_date,
                is_network_destination,
                latest_snapshot_date,
                mount_point,
                network_url,
                server_display_name,
                snapshot_count,
                time_capsule_display_name,
                volume_display_name,
                destinations
            FROM
                $this->tableNameV2");
        }
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
