Timemachine module
==================

Reports on Time Machine backup information 

Retrieves information from syslog and Time Machine plist

The following information is stored in the table:

* id - Unique ID
* last_success - Datetime of last successful backup
* last_failure - Datetime of last failure
* last_failure_msg - Message of the last failure
* duration - int - Duration in seconds
* always_show_deleted_backups_warning - int/bool - Will Time Machine display warning if deleting older backups
* auto_backup - int/bool - Are automatic backups enabled
* bytes_available - int - Number of available bytes on backup destination
* bytes_used - int - Number of used bytes on backup destination
* consistency_scan_date - date backup destination was last scanned for consistency
* date_of_latest_warning - date of last no backups warning
* destination_id - UUID of destination
* destination_uuids - all destination UUIDs
* last_known_encryption_state - at last backup, was drive encrypted
* result - result code of backup
* root_volume_uuid - UUID of root volume
* snapshot_dates - TEXT - dates of all backup snapshots
* exclude_by_path - TEXT - folder/files automatically skipped by system
* host_uuids - UUIDs of known hosts
* last_configuration_trace_date - date of last configuration trace
* last_destination_id - last UUID of backup destination
* localized_disk_image_volume_name - localized name of volume
* mobile_backups - int/bool - are mobile backups enabled
* skip_paths - TEXT - files/folders excluded by backup, set by users
* skip_system_files - int/bool - skip system files in backups
* alias_volume_name - name of destination volume
* earliest_snapshot_date - oldest snapshot date
* is_network_destination - int/bool - is backup destination a network share
* latest_snapshot_date - most recent snapshot
* mount_point - mount point of destination
* network_url - URL of network share
* server_display_name - name of network share
* snapshot_count - number of snapshots for system in backup
* time_capsule_display_name - name of Time Capsule
* volume_display_name - name of local backup disk
* destinations - int - number of destinations

