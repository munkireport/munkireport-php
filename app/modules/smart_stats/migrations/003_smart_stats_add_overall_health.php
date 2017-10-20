<?php

class Migration_smart_stats_add_overall_health extends \Model
{
	protected $columnname = 'overall_health';

	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'smart_stats';
	}


	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->beginTransaction();
		$sql = sprintf(
			'ALTER TABLE %s ADD COLUMN %s VARCHAR(255) DEFAULT NULL',
			$this->enquote($this->tablename),
			$this->enquote($this->columnname)
		);

		$this->exec($sql);

		// Adding an index
		$idx_name = $this->tablename . '_' . $this->columnname;
		$sql = sprintf(
		    "CREATE INDEX %s ON %s (%s)",
		    $idx_name,
		    $this->enquote($this->tablename),
		    $this->enquote($this->columnname)
		);
		$this->exec($sql);
		
		$dbh->commit();

	}


	public function down()
	{
		// Get database handle
		$dbh = $this->getdbh();
		switch ($this->get_driver()) {
		case 'sqlite':

			$dbh->beginTransaction();
			
			// Create temp table
			$sql = "CREATE TABLE `smart_stats_temp` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`serial_number` varchar(255) DEFAULT NULL,
				`disk_number` int(11) DEFAULT NULL,
				`model_family` varchar(255) DEFAULT NULL,
				`device_model` varchar(255) DEFAULT NULL,
				`serial_number_hdd` varchar(255) DEFAULT NULL,
				`lu_wwn_device_id` varchar(255) DEFAULT NULL,
				`firmware_version` varchar(255) DEFAULT NULL,
				`user_capacity` varchar(255) DEFAULT NULL,
				`sector_size` varchar(255) DEFAULT NULL,
				`rotation_rate` varchar(255) DEFAULT NULL,
				`device_is` varchar(255) DEFAULT NULL,
				`ata_version_is` varchar(255) DEFAULT NULL,
				`sata_version_is` varchar(255) DEFAULT NULL,
				`form_factor` varchar(255) DEFAULT NULL,
				`smart_support_is` varchar(255) DEFAULT NULL,
				`smart_is` varchar(255) DEFAULT NULL,
				`error_count` int(11) DEFAULT NULL,
				`error_poh` int(11) DEFAULT NULL,
				`timestamp` int(11) DEFAULT NULL,
				`raw_read_error_rate` bigint(20) DEFAULT NULL,
				`throughput_performance` bigint(20) DEFAULT NULL,
				`spin_up_time` bigint(20) DEFAULT NULL,
				`start_stop_count` bigint(20) DEFAULT NULL,
				`reallocated_sector_ct` bigint(20) DEFAULT NULL,
				`read_channel_margin` bigint(20) DEFAULT NULL,
				`seek_error_rate` bigint(20) DEFAULT NULL,
				`seek_time_performance` bigint(20) DEFAULT NULL,
				`power_on_hours` bigint(20) DEFAULT NULL,
				`power_on_hours_and_msec` varchar(255) DEFAULT NULL,
				`spin_retry_count` bigint(20) DEFAULT NULL,
				`calibration_retry_count` bigint(20) DEFAULT NULL,
				`power_cycle_count` bigint(20) DEFAULT NULL,
				`read_soft_error_rate` bigint(20) DEFAULT NULL,
				`program_fail_count_chip` bigint(20) DEFAULT NULL,
				`erase_fail_count_chip` bigint(20) DEFAULT NULL,
				`wear_leveling_count` bigint(20) DEFAULT NULL,
				`used_rsvd_blk_cnt_chip` bigint(20) DEFAULT NULL,
				`used_rsvd_blk_cnt_tot` bigint(20) DEFAULT NULL,
				`unused_rsvd_blk_cnt_tot` bigint(20) DEFAULT NULL,
				`program_fail_cnt_total` bigint(20) DEFAULT NULL,
				`erase_fail_count_total` bigint(20) DEFAULT NULL,
				`runtime_bad_block` bigint(20) DEFAULT NULL,
				`endtoend_error` bigint(20) DEFAULT NULL,
				`reported_uncorrect` bigint(20) DEFAULT NULL,
				`command_timeout` bigint(20) DEFAULT NULL,
				`high_fly_writes` bigint(20) DEFAULT NULL,
				`airflow_temperature_cel` varchar(255) DEFAULT NULL,
				`gsense_error_rate` bigint(20) DEFAULT NULL,
				`poweroff_retract_count` bigint(20) DEFAULT NULL,
				`load_cycle_count` bigint(20) DEFAULT NULL,
				`temperature_celsius` varchar(255) DEFAULT NULL,
				`hardware_ecc_recovered` bigint(20) DEFAULT NULL,
				`reallocated_event_count` bigint(20) DEFAULT NULL,
				`current_pending_sector` bigint(20) DEFAULT NULL,
				`offline_uncorrectable` bigint(20) DEFAULT NULL,
				`udma_crc_error_count` bigint(20) DEFAULT NULL,
				`multi_zone_error_rate` bigint(20) DEFAULT NULL,
				`soft_read_error_rate` bigint(20) DEFAULT NULL,
				`data_address_mark_errs` bigint(20) DEFAULT NULL,
				`run_out_cancel` bigint(20) DEFAULT NULL,
				`soft_ecc_correction` bigint(20) DEFAULT NULL,
				`thermal_asperity_rate` bigint(20) DEFAULT NULL,
				`flying_height` bigint(20) DEFAULT NULL,
				`spin_high_current` bigint(20) DEFAULT NULL,
				`spin_buzz` bigint(20) DEFAULT NULL,
				`offline_seek_performnce` bigint(20) DEFAULT NULL,
				`disk_shift` bigint(20) DEFAULT NULL,
				`loaded_hours` bigint(20) DEFAULT NULL,
				`load_retry_count` bigint(20) DEFAULT NULL,
				`load_friction` bigint(20) DEFAULT NULL,
				`loadin_time` bigint(20) DEFAULT NULL,
				`torqamp_count` bigint(20) DEFAULT NULL,
				`head_amplitude` bigint(20) DEFAULT NULL,
				`available_reservd_space` bigint(20) DEFAULT NULL,
				`media_wearout_indicator` bigint(20) DEFAULT NULL,
				`head_flying_hours` bigint(20) DEFAULT NULL,
				`total_lbas_written` bigint(20) DEFAULT NULL,
				`total_lbas_read` bigint(20) DEFAULT NULL,
				`read_error_retry_rate` bigint(20) DEFAULT NULL,
				`free_fall_sensor` bigint(20) DEFAULT NULL,
				`host_reads_mib` bigint(20) DEFAULT NULL,
				`host_writes_mib` bigint(20) DEFAULT NULL,
				`grown_failing_block_ct` bigint(20) DEFAULT NULL,
				`unexpect_power_loss_ct` bigint(20) DEFAULT NULL,
				`non4k_aligned_access` bigint(20) DEFAULT NULL,
				`sata_iface_downshift` bigint(20) DEFAULT NULL,
				`factory_bad_block_ct` bigint(20) DEFAULT NULL,
				`percent_lifetime_used` bigint(20) DEFAULT NULL,
				`write_error_rate` bigint(20) DEFAULT NULL,
				`success_rain_recov_cnt` bigint(20) DEFAULT NULL,
				`total_host_sector_write` bigint(20) DEFAULT NULL,
				`host_program_page_count` bigint(20) DEFAULT NULL,
				`bckgnd_program_page_cnt` bigint(20) DEFAULT NULL,
				`perc_rated_life_used` bigint(20) DEFAULT NULL,
				`reallocate_nand_blk_cnt` bigint(20) DEFAULT NULL,
				`ave_blockerase_count` bigint(20) DEFAULT NULL,
				`Unused_Reserve_NAND_Blk` int(11) DEFAULT NULL,
				`sata_interfac_downshift` bigint(20) DEFAULT NULL,
				`ssd_life_left` bigint(20) DEFAULT NULL,
				`life_curve_status` bigint(20) DEFAULT NULL,
				`supercap_health` bigint(20) DEFAULT NULL,
				`lifetime_writes_gib` bigint(20) DEFAULT NULL,
				`lifetime_reads_gib` bigint(20) DEFAULT NULL,
				`uncorrectable_error_cnt` bigint(20) DEFAULT NULL,
				`ecc_error_eate` bigint(20) DEFAULT NULL,
				`crc_error_count` bigint(20) DEFAULT NULL,
				`supercap_status` bigint(20) DEFAULT NULL,
				`exception_mode_status` bigint(20) DEFAULT NULL,
				`por_recovery_count` bigint(20) DEFAULT NULL,
				`total_reads_gib` bigint(20) DEFAULT NULL,
				`total_writes_gib` bigint(20) DEFAULT NULL,
				`thermal_throttle` bigint(20) DEFAULT NULL,
				`perc_writeerase_count` bigint(20) DEFAULT NULL,
				`perc_avail_resrvd_space` bigint(20) DEFAULT NULL,
				`perc_writeerase_ct_bc` bigint(20) DEFAULT NULL,
				`avg_writeerase_count` bigint(20) DEFAULT NULL,
				`sata_phy_error` bigint(20) DEFAULT NULL
				)";
			$this->exec($sql);
			
			// Copy data into temp table
			$sql = "INSERT INTO smart_stats_temp (
					SELECT id, serial_number, disk_number, model_family, device_model, serial_number_hdd, lu_wwn_device_id, firmware_version, user_capacity, sector_size, rotation_rate, device_is, ata_version_is, sata_version_is, form_factor, smart_support_is, smart_is, error_count, error_poh, timestamp, raw_read_error_rate, throughput_performance, spin_up_time, start_stop_count, reallocated_sector_ct, read_channel_margin, seek_error_rate, seek_time_performance, power_on_hours, power_on_hours_and_msec, spin_retry_count, calibration_retry_count, power_cycle_count, read_soft_error_rate, program_fail_count_chip, erase_fail_count_chip, wear_leveling_count, used_rsvd_blk_cnt_chip, used_rsvd_blk_cnt_tot, unused_rsvd_blk_cnt_tot, program_fail_cnt_total, erase_fail_count_total, runtime_bad_block, endtoend_error, reported_uncorrect, command_timeout, high_fly_writes, airflow_temperature_cel, gsense_error_rate, poweroff_retract_count, load_cycle_count, temperature_celsius, hardware_ecc_recovered, reallocated_event_count, current_pending_sector, offline_uncorrectable, udma_crc_error_count, multi_zone_error_rate, soft_read_error_rate, data_address_mark_errs, run_out_cancel, soft_ecc_correction, thermal_asperity_rate, flying_height, spin_high_current, spin_buzz, offline_seek_performnce, disk_shift, loaded_hours, load_retry_count, load_friction, loadin_time, torqamp_count, head_amplitude, available_reservd_space, media_wearout_indicator, head_flying_hours, total_lbas_written, total_lbas_read, read_error_retry_rate, free_fall_sensor, host_reads_mib, host_writes_mib, grown_failing_block_ct, unexpect_power_loss_ct, non4k_aligned_access, sata_iface_downshift, factory_bad_block_ct, percent_lifetime_used, write_error_rate, success_rain_recov_cnt, total_host_sector_write, host_program_page_count, bckgnd_program_page_cnt, perc_rated_life_used, reallocate_nand_blk_cnt, ave_blockerase_count, Unused_Reserve_NAND_Blk, sata_interfac_downshift, ssd_life_left, life_curve_status, supercap_health, lifetime_writes_gib, lifetime_reads_gib, uncorrectable_error_cnt, ecc_error_eate, crc_error_count, supercap_status, exception_mode_status, por_recovery_count, total_reads_gib, total_writes_gib, thermal_throttle, perc_writeerase_count, perc_avail_resrvd_space, perc_writeerase_ct_bc, avg_writeerase_count, sata_phy_error
					FROM smart_stats";
			$this->exec($sql);

			// Drop old table and rename temp
			$sql = "DROP table smart_stats";
			$this->exec($sql);

			$sql = "ALTER TABLE smart_stats_temp RENAME TO smart_stats";
			$this->exec($sql);

			break;
		
		default:
			$sql = sprintf(
				'ALTER TABLE %s DROP COLUMN %s',
				$this->enquote($this->tablename),
				$this->enquote($this->columnname)
			);
			$this->exec($sql);
		}
	}
}
