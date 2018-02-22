<?php

use CFPropertyList\CFPropertyList;

class Smart_stats_model extends \Model {
    
	function __construct($serial='')
	{
        parent::__construct('id', 'smart_stats'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['disk_number'] = 0;
        $this->rs['model_family'] = '';
        $this->rs['device_model'] = '';
        $this->rs['serial_number_hdd'] = '';
        $this->rs['lu_wwn_device_id'] = '';
        $this->rs['firmware_version'] = '';
        $this->rs['user_capacity'] = '';
        $this->rs['sector_size'] = '';
        $this->rs['rotation_rate'] = '';
        $this->rs['device_is'] = '';
        $this->rs['ata_version_is'] = '';
        $this->rs['sata_version_is'] = '';
        $this->rs['form_factor'] = '';
        $this->rs['smart_support_is'] = '';
        $this->rs['smart_is'] = '';
        $this->rs['error_count'] = 0;
        $this->rs['error_poh'] = 0;
        $this->rs['timestamp'] = 0; // Unix time when the report was uploaded
        $this->rs['raw_read_error_rate'] = 0; $this->rt['raw_read_error_rate'] = 'BIGINT';
        $this->rs['throughput_performance'] = 0; $this->rt['throughput_performance'] = 'BIGINT';
        $this->rs['spin_up_time'] = 0; $this->rt['spin_up_time'] = 'BIGINT';
        $this->rs['start_stop_count'] = 0; $this->rt['start_stop_count'] = 'BIGINT'; 
        $this->rs['reallocated_sector_ct'] = 0; $this->rt['reallocated_sector_ct'] = 'BIGINT';
        $this->rs['read_channel_margin'] = 0; $this->rt['read_channel_margin'] = 'BIGINT'; 
        $this->rs['seek_error_rate'] = 0; $this->rt['seek_error_rate'] = 'BIGINT'; 
        $this->rs['seek_time_performance'] = 0; $this->rt['seek_time_performance'] = 'BIGINT';
        $this->rs['power_on_hours'] = 0; $this->rt['power_on_hours'] = 'BIGINT'; 
        $this->rs['power_on_hours_and_msec'] = ''; 
        $this->rs['spin_retry_count'] = 0; $this->rt['spin_retry_count'] = 'BIGINT';
        $this->rs['calibration_retry_count'] = 0; $this->rt['calibration_retry_count'] = 'BIGINT';
        $this->rs['power_cycle_count'] = 0; $this->rt['power_cycle_count'] = 'BIGINT'; 
        $this->rs['read_soft_error_rate'] = 0; $this->rt['read_soft_error_rate'] = 'BIGINT'; 
        $this->rs['program_fail_count_chip'] = 0; $this->rt['program_fail_count_chip'] = 'BIGINT';
        $this->rs['erase_fail_count_chip'] = 0; $this->rt['erase_fail_count_chip'] = 'BIGINT';
        $this->rs['wear_leveling_count'] = 0; $this->rt['wear_leveling_count'] = 'BIGINT';
        $this->rs['used_rsvd_blk_cnt_chip'] = 0; $this->rt['used_rsvd_blk_cnt_chip'] = 'BIGINT';
        $this->rs['used_rsvd_blk_cnt_tot'] = 0; $this->rt['used_rsvd_blk_cnt_tot'] = 'BIGINT';
        $this->rs['unused_rsvd_blk_cnt_tot'] = 0; $this->rt['unused_rsvd_blk_cnt_tot'] = 'BIGINT';
        $this->rs['program_fail_cnt_total'] = 0; $this->rt['program_fail_cnt_total'] = 'BIGINT';
        $this->rs['erase_fail_count_total'] = 0; $this->rt['erase_fail_count_total'] = 'BIGINT';
        $this->rs['runtime_bad_block'] = 0; $this->rt['runtime_bad_block'] = 'BIGINT';
        $this->rs['endtoend_error'] = 0; $this->rt['endtoend_error'] = 'BIGINT';
        $this->rs['reported_uncorrect'] = 0; $this->rt['reported_uncorrect'] = 'BIGINT';
        $this->rs['command_timeout'] = 0; $this->rt['command_timeout'] = 'BIGINT';
        $this->rs['high_fly_writes'] = 0; $this->rt['high_fly_writes'] = 'BIGINT';
        $this->rs['airflow_temperature_cel'] = '';
        $this->rs['gsense_error_rate'] = 0; $this->rt['gsense_error_rate'] = 'BIGINT';
        $this->rs['poweroff_retract_count'] = 0; $this->rt['poweroff_retract_count'] = 'BIGINT';
        $this->rs['load_cycle_count'] = 0; $this->rt['load_cycle_count'] = 'BIGINT';
        $this->rs['temperature_celsius'] = '';
        $this->rs['hardware_ecc_recovered'] = 0; $this->rt['hardware_ecc_recovered'] = 'BIGINT';
        $this->rs['reallocated_event_count'] = 0; $this->rt['reallocated_event_count'] = 'BIGINT';
        $this->rs['current_pending_sector'] = 0; $this->rt['current_pending_sector'] = 'BIGINT';
        $this->rs['offline_uncorrectable'] = 0; $this->rt['offline_uncorrectable'] = 'BIGINT';
        $this->rs['udma_crc_error_count'] = 0; $this->rt['udma_crc_error_count'] = 'BIGINT';
        $this->rs['multi_zone_error_rate'] = 0; $this->rt['multi_zone_error_rate'] = 'BIGINT';
        $this->rs['soft_read_error_rate'] = 0; $this->rt['soft_read_error_rate'] = 'BIGINT';
        $this->rs['data_address_mark_errs'] = 0; $this->rt['data_address_mark_errs'] = 'BIGINT';
        $this->rs['run_out_cancel'] = 0; $this->rt['run_out_cancel'] = 'BIGINT';
        $this->rs['soft_ecc_correction'] = 0; $this->rt['soft_ecc_correction'] = 'BIGINT';
        $this->rs['thermal_asperity_rate'] = 0; $this->rt['thermal_asperity_rate'] = 'BIGINT';
        $this->rs['flying_height'] = 0; $this->rt['flying_height'] = 'BIGINT';
        $this->rs['spin_high_current'] = 0; $this->rt['spin_high_current'] = 'BIGINT';
        $this->rs['spin_buzz'] = 0; $this->rt['spin_buzz'] = 'BIGINT';
        $this->rs['offline_seek_performnce'] = 0; $this->rt['offline_seek_performnce'] = 'BIGINT';	
        $this->rs['disk_shift'] = 0; $this->rt['disk_shift'] = 'BIGINT';
        $this->rs['loaded_hours'] = 0; $this->rt['loaded_hours'] = 'BIGINT';
        $this->rs['load_retry_count'] = 0; $this->rt['load_retry_count'] = 'BIGINT';
        $this->rs['load_friction'] = 0; $this->rt['load_friction'] = 'BIGINT';
        $this->rs['loadin_time'] = 0; $this->rt['loadin_time'] = 'BIGINT';
        $this->rs['torqamp_count'] = 0; $this->rt['torqamp_count'] = 'BIGINT';
        $this->rs['head_amplitude'] = 0; $this->rt['head_amplitude'] = 'BIGINT';
        $this->rs['available_reservd_space'] = 0; $this->rt['available_reservd_space'] = 'BIGINT';
        $this->rs['media_wearout_indicator'] = 0; $this->rt['media_wearout_indicator'] = 'BIGINT';
        $this->rs['head_flying_hours'] = 0; $this->rt['head_flying_hours'] = 'BIGINT';
        $this->rs['total_lbas_written'] = 0; $this->rt['total_lbas_written'] = 'BIGINT';
        $this->rs['total_lbas_read'] = 0; $this->rt['total_lbas_read'] = 'BIGINT';
        $this->rs['read_error_retry_rate'] = 0; $this->rt['read_error_retry_rate'] = 'BIGINT';
        $this->rs['free_fall_sensor'] = 0; $this->rt['free_fall_sensor'] = 'BIGINT';
        $this->rs['host_reads_mib'] = 0; $this->rt['host_reads_mib'] = 'BIGINT';
        $this->rs['host_writes_mib'] = 0; $this->rt['host_writes_mib'] = 'BIGINT';
        $this->rs['grown_failing_block_ct'] = 0; $this->rt['grown_failing_block_ct'] = 'BIGINT';
        $this->rs['unexpect_power_loss_ct'] = 0; $this->rt['unexpect_power_loss_ct'] = 'BIGINT';
        $this->rs['non4k_aligned_access'] = 0; $this->rt['non4k_aligned_access'] = 'BIGINT';
        $this->rs['sata_iface_downshift'] = 0; $this->rt['sata_iface_downshift'] = 'BIGINT';
        $this->rs['factory_bad_block_ct'] = 0; $this->rt['factory_bad_block_ct'] = 'BIGINT';
        $this->rs['percent_lifetime_used'] = 0; $this->rt['percent_lifetime_used'] = 'BIGINT';
        $this->rs['write_error_rate'] = 0; $this->rt['write_error_rate'] = 'BIGINT';
        $this->rs['success_rain_recov_cnt'] = 0; $this->rt['success_rain_recov_cnt'] = 'BIGINT';
        $this->rs['total_host_sector_write'] = 0; $this->rt['total_host_sector_write'] = 'BIGINT';
        $this->rs['host_program_page_count'] = 0; $this->rt['host_program_page_count'] = 'BIGINT';
        $this->rs['bckgnd_program_page_cnt'] = 0; $this->rt['bckgnd_program_page_cnt'] = 'BIGINT';
        $this->rs['perc_rated_life_used'] = 0; $this->rt['perc_rated_life_used'] = 'BIGINT';
        $this->rs['reallocate_nand_blk_cnt'] = 0; $this->rt['reallocate_nand_blk_cnt'] = 'BIGINT';
        $this->rs['ave_blockerase_count'] = 0; $this->rt['ave_blockerase_count'] = 'BIGINT';
        $this->rs['Unused_Reserve_NAND_Blk'] = 0; $this->rt['Unused_Reserve_NAND_Blk'] = 'BIGINT';
        $this->rs['sata_interfac_downshift'] = 0; $this->rt['sata_interfac_downshift'] = 'BIGINT';
        $this->rs['ssd_life_left'] = 0; $this->rt['ssd_life_left'] = 'BIGINT';
        $this->rs['life_curve_status'] = 0; $this->rt['life_curve_status'] = 'BIGINT';
        $this->rs['supercap_health'] = 0; $this->rt['supercap_health'] = 'BIGINT';
        $this->rs['lifetime_writes_gib'] = 0; $this->rt['lifetime_writes_gib'] = 'BIGINT';
        $this->rs['lifetime_reads_gib'] = 0; $this->rt['lifetime_reads_gib'] = 'BIGINT';
        $this->rs['uncorrectable_error_cnt'] = 0; $this->rt['uncorrectable_error_cnt'] = 'BIGINT';
        $this->rs['ecc_error_eate'] = 0; $this->rt['ecc_error_eate'] = 'BIGINT';
        $this->rs['crc_error_count'] = 0; $this->rt['crc_error_count'] = 'BIGINT';
        $this->rs['supercap_status'] = 0; $this->rt['supercap_status'] = 'BIGINT';
        $this->rs['exception_mode_status'] = 0; $this->rt['exception_mode_status'] = 'BIGINT';
        $this->rs['por_recovery_count'] = 0; $this->rt['por_recovery_count'] = 'BIGINT';
        $this->rs['total_reads_gib'] = 0; $this->rt['total_reads_gib'] = 'BIGINT';
        $this->rs['total_writes_gib'] = 0; $this->rt['total_writes_gib'] = 'BIGINT';
        $this->rs['thermal_throttle'] = 0; $this->rt['thermal_throttle'] = 'BIGINT';
        $this->rs['perc_writeerase_count'] = 0; $this->rt['perc_writeerase_count'] = 'BIGINT';
        $this->rs['perc_avail_resrvd_space'] = 0; $this->rt['perc_avail_resrvd_space'] = 'BIGINT';
        $this->rs['perc_writeerase_ct_bc'] = 0; $this->rt['perc_writeerase_ct_bc'] = 'BIGINT';
        $this->rs['avg_writeerase_count'] = 0; $this->rt['avg_writeerase_count'] = 'BIGINT';
        $this->rs['sata_phy_error'] = 0; $this->rt['sata_phy_error'] = 'BIGINT';
        $this->rs['overall_health'] = '';
        $this->rs['pci_vender_subsystem_id'] = ''; // Start of NVMe columns
        $this->rs['model_number'] = '';
        $this->rs['temperature_nvme'] = 0;
        $this->rs['power_on_hours_nvme'] = 0;
        $this->rs['power_cycle_count_nvme'] = 0;
        $this->rs['critical_warning'] = '';  
        $this->rs['available_spare'] = 0; $this->rt['available_spare'] = 'BIGINT';
        $this->rs['available_spare_threshold'] = 0; $this->rt['available_spare_threshold'] = 'BIGINT';
        $this->rs['percentage_used'] = 0; $this->rt['percentage_used'] = 'BIGINT';
        $this->rs['data_units_read'] = '';
        $this->rs['data_units_written'] = '';
        $this->rs['host_read_commands'] = 0; $this->rt['host_read_commands'] = 'BIGINT';
        $this->rs['host_write_commands'] = 0; $this->rt['host_write_commands'] = 'BIGINT';
        $this->rs['controller_busy_time'] = 0; $this->rt['controller_busy_time'] = 'BIGINT';
        $this->rs['unsafe_shutdowns'] = 0; $this->rt['unsafe_shutdowns'] = 'BIGINT';
        $this->rs['media_data_integrity_errors'] = 0; $this->rt['media_data_integrity_errors'] = 'BIGINT';
        $this->rs['error_info_log_entries'] = 0; $this->rt['error_info_log_entries'] = 'BIGINT';
        $this->rs['ieee_oui_id'] = '';
        $this->rs['controller_id'] = 0; $this->rt['controller_id'] = 'BIGINT';
        $this->rs['number_of_namespaces'] = 0; $this->rt['number_of_namespaces'] = 'BIGINT';
        $this->rs['firmware_updates'] = '';
        $this->rs['optional_admin_commands'] = ''; $this->rt['optional_admin_commands'] = 'TEXT';
        $this->rs['optional_nvm_commands'] = ''; $this->rt['optional_nvm_commands'] = 'TEXT';
        $this->rs['max_data_transfer_size'] = ''; // End of new NVMe columns 

        // Schema version, increment when creating a db migration
        $this->schema_version = 4;

        // Indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('disk_number');
        $this->idx[] = array('power_on_hours_and_msec');
        $this->idx[] = array('power_on_hours');
        $this->idx[] = array('model_family');
        $this->idx[] = array('device_model');
        $this->idx[] = array('serial_number_hdd');
        $this->idx[] = array('lu_wwn_device_id');
        $this->idx[] = array('firmware_version');
        $this->idx[] = array('user_capacity');
        $this->idx[] = array('sector_size');
        $this->idx[] = array('rotation_rate');
        $this->idx[] = array('device_is');
        $this->idx[] = array('ata_version_is');
        $this->idx[] = array('sata_version_is');
        $this->idx[] = array('form_factor');
        $this->idx[] = array('smart_support_is');
        $this->idx[] = array('smart_is');
        $this->idx[] = array('error_count');
        $this->idx[] = array('error_poh');
        $this->idx[] = array('timestamp');
        $this->idx[] = array('overall_health');
				        
		// Create table if it does not exist
		//$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_record($serial);
		}
		
		$this->serial = $serial;
    }

    public function getSmartStats()
    {
        $sql = "SELECT COUNT(CASE WHEN overall_health='PASSED' THEN 1 END) AS passed,
						COUNT(CASE WHEN overall_health='UNKNOWN!' THEN 1 END) AS unknown,
						COUNT(CASE WHEN overall_health='FAILED!' THEN 1 END) AS failed
						FROM smart_stats
						LEFT JOIN reportdata USING(serial_number)
						".get_machine_group_filter();
        return current($this->query($sql));
    }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author tuxudo
     * 
     **/
    function process($data)
    {
        // If data is empty, throw error
        if (! $data) {
            throw new Exception("Error Processing SMART Stats Module Request: No data found", 1);
        }
        
        // Translate smart stat strings to db fields
        $translate = array(
        	'Raw_Read_Error_Rate' => 'raw_read_error_rate',
        	'Throughput_Performance' => 'throughput_performance',
        	'Spin_Up_Time' => 'spin_up_time',
        	'Start_Stop_Count' => 'start_stop_count',
        	'Reallocated_Sector_Ct' => 'reallocated_sector_ct',
        	'Read_Channel_Margin' => 'read_channel_margin',
        	'Seek_Error_Rate' => 'seek_error_rate',
        	'Seek_Time_Performance' => 'seek_time_performance',
        	'Power_On_Hours' => 'power_on_hours',
        	'Spin_Retry_Count' => 'spin_retry_count',
        	'Calibration_Retry_Count' => 'calibration_retry_count',
        	'Power_Cycle_Count' => 'power_cycle_count',
        	'Power_On_Hours_and_Msec' => 'power_on_hours_and_msec', 
        	'Read_Soft_Error_Rate' => 'read_soft_error_rate',
        	'Program_Fail_Count_Chip' => 'program_fail_count_chip',
        	'Erase_Fail_Count_Chip' => 'erase_fail_count_chip',
        	'Wear_Leveling_Count' => 'wear_leveling_count',
        	'Used_Rsvd_Blk_Cnt_Chip' => 'used_rsvd_blk_cnt_chip',
        	'Used_Rsvd_Blk_Cnt_Tot' => 'used_rsvd_blk_cnt_tot',
        	'Unused_Rsvd_Blk_Cnt_Tot' => 'unused_rsvd_blk_cnt_tot',
        	'Program_Fail_Cnt_Total' => 'program_fail_cnt_total',
        	'Erase_Fail_Count_Total' => 'erase_fail_count_total',
        	'Runtime_Bad_Block' => 'runtime_bad_block',
        	'EndtoEnd_Error' => 'endtoend_error',
        	'Reported_Uncorrect' => 'reported_uncorrect',
        	'Command_Timeout' => 'command_timeout',
        	'High_Fly_Writes' => 'high_fly_writes',
        	'Airflow_Temperature_Cel' => 'airflow_temperature_cel',
        	'GSense_Error_Rate' => 'gsense_error_rate',
        	'PowerOff_Retract_Count' => 'poweroff_retract_count',
        	'Load_Cycle_Count' => 'load_cycle_count',
        	'Temperature_Celsius' => 'temperature_celsius',
        	'Hardware_ECC_Recovered' => 'hardware_ecc_recovered',
        	'Reallocated_Event_Count' => 'reallocated_event_count',
        	'Current_Pending_Sector' => 'current_pending_sector',
        	'Offline_Uncorrectable' => 'offline_uncorrectable',
        	'UDMA_Error_Count' => 'udma_crc_error_count',
        	'Multi_Zone_Error_Rate' => 'multi_zone_error_rate',
        	'Soft_Read_Error_Rate' => 'soft_read_error_rate',
        	'Data_Address_Mark_Errs' => 'data_address_mark_errs',
        	'Run_Out_Cancel' => 'run_out_cancel',
        	'Soft_ECC_Correction' => 'soft_ecc_correction',
        	'Thermal_Asperity_Rate' => 'thermal_asperity_rate',
        	'Flying_Height' => 'flying_height',
        	'Spin_High_Current' => 'spin_high_current',
        	'Spin_Buzz' => 'spin_buzz',
        	'Offline_Seek_Performnce' => 'offline_seek_performnce',
        	'Disk_Shift' => 'disk_shift',
        	'Loaded_Hours' => 'loaded_hours',
        	'Load_Retry_Count' => 'load_retry_count',
        	'Load_Friction' => 'load_friction',
        	'Loadin_Time' => 'loadin_time',
        	'Torqamp_Count' => 'torqamp_count',
        	'Head_Amplitude' => 'head_amplitude',
        	'Available_Reservd_Space' => 'available_reservd_space',
        	'Media_Wearout_Indicator' => 'media_wearout_indicator',
        	'Head_Flying_Hours' => 'head_flying_hours',
        	'Total_LBAs_Written' => 'total_lbas_written',
        	'Total_LBAs_Read' => 'total_lbas_read',
        	'Read_Error_Retry_Rate' => 'read_error_retry_rate',
        	'Host_Reads_MiB' => 'host_reads_mib',
        	'Host_Writes_MiB' => 'host_writes_mib',
        	'Grown_Failing_Block_Ct' => 'grown_failing_block_ct',
        	'Unexpect_Power_Loss_Ct' => 'unexpect_power_loss_ct',
        	'Non4k_Aligned_Access' => 'non4k_aligned_access',
        	'SATA_Iface_Downshift' => 'sata_iface_downshift',
        	'Factory_Bad_Block_Ct' => 'factory_bad_block_ct',
        	'Percent_Lifetime_Used' => 'percent_lifetime_used',
        	'Write_Error_Rate' => 'write_error_rate',
        	'Success_RAIN_Recov_Cnt' => 'success_rain_recov_cnt',
        	'Total_Host_Sector_Write' => 'total_host_sector_write',
        	'Host_Program_Page_Count' => 'host_program_page_count',
        	'Bckgnd_Program_Page_Cnt' => 'bckgnd_program_page_cnt',
        	'Perc_Rated_Life_Used' => 'perc_rated_life_used',
        	'Reallocate_NAND_Blk_Cnt' => 'reallocate_nand_blk_cnt',
        	'Ave_BlockErase_Count' => 'ave_blockerase_count',
        	'Unused_Reserve_NAND_Blk' => 'unused_reserve_nand_blk',
        	'SATA_Interfac_Downshift'  => 'sata_interfac_downshift',
        	'SSD_Life_Left' => 'ssd_life_left',
        	'Life_Curve_Status' => 'life_curve_status',
        	'SuperCap_Health' => 'supercap_health',
        	'Lifetime_Writes_GiB' => 'lifetime_writes_gib',
        	'Lifetime_Reads_GiB' => 'lifetime_reads_gib',
        	'Uncorrectable_Error_Cnt' => 'uncorrectable_error_cnt',
        	'ECC_Error_Rate' => 'ecc_error_eate',
        	'CRC_Error_Count' => 'crc_error_count',
        	'Supercap_Status' => 'supercap_status',
        	'Exception_Mode_Status' => 'exception_mode_status',
        	'POR_Recovery_Count' => 'por_recovery_count',
        	'Total_Reads_GiB' => 'total_reads_gib',
        	'Total_Writes_GiB' => 'total_writes_gib',
        	'Thermal_Throttle' => 'thermal_throttle',
        	'Perc_WriteErase_Count' => 'perc_writeerase_count',
        	'Perc_Avail_Resrvd_Space' => 'perc_avail_resrvd_space',
        	'Perc_WriteErase_Ct_BC' => 'perc_writeerase_ct_bc',
        	'SATA_PHY_Error' => 'sata_phy_error',
        	'Avg_WriteErase_Count' => 'avg_writeerase_count',
        	'Free_Fall_Sensor' => 'free_fall_sensor',
        	'ModelFamily' => 'model_family',
        	'DeviceModel' => 'device_model',
        	'SerialNumber' => 'serial_number_hdd',
        	'LUWWNDeviceID' => 'lu_wwn_device_id',
        	'FirmwareVersion' => 'firmware_version',
        	'UserCapacity' => 'user_capacity',
        	'SectorSize' => 'sector_size',
        	'RotationRate' => 'rotation_rate',
        	'Deviceis' => 'device_is',
        	'ATAVersionis' => 'ata_version_is',
        	'SATAVersionis' => 'sata_version_is',
        	'FormFactor' => 'form_factor',
        	'ErrorPoH' => 'error_poh',
        	'error_count' => 'error_count',
        	'SMARTsupportis' => 'smart_support_is',
        	'DiskNumber' => 'disk_number',
        	'SMARTis' => 'smart_is',
        	'CriticalWarning' => 'critical_warning', // Start of NVMe translations
        	'AvailableSpare' => 'available_spare',
        	'AvailableSpareThreshold' => 'available_spare_threshold',
        	'PercentageUsed' => 'percentage_used',
        	'DataUnitsRead' => 'data_units_read',
        	'DataUnitsWritten' => 'data_units_written',
        	'HostReadCommands' => 'host_read_commands',
        	'HostWriteCommands' => 'host_write_commands',
        	'ControllerBusyTime' => 'controller_busy_time',
        	'UnsafeShutdowns' => 'unsafe_shutdowns',
        	'MediaandDataIntegrityErrors' => 'media_data_integrity_errors',
        	'ErrorInformationLogEntries' => 'error_info_log_entries',
        	'PCIVendorSubsystemID' => 'pci_vender_subsystem_id',
        	'IEEEOUIIdentifier' => 'ieee_oui_id',
        	'ControllerID' => 'controller_id',
        	'NumberofNamespaces' => 'number_of_namespaces',
        	'FirmwareUpdates0x06' => 'firmware_updates',
        	'OptionalAdminCommands0x0006' => 'optional_admin_commands',
        	'OptionalNVMCommands0x001f' => 'optional_nvm_commands',
        	'ModelNumber' => 'model_number',
        	'Temperature' => 'temperature_nvme',
        	'PowerCycles' => 'power_cycle_count_nvme',
        	'PowerOnHours' => 'power_on_hours_nvme',
        	'MaximumDataTransferSize' => 'max_data_transfer_size', // End of NVMe translations  
        	'Overall_Health' => 'overall_health');

        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Process incoming smart_stats.xml
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $plist = $parser->toArray();
        
        // Array of string for nulling with ""
        $strings =  array('model_family','device_model','serial_number_hdd','lu_wwn_device_id','firmware_version','user_capacity','sector_size','rotation_rate','device_is','ata_version_is','sata_version_is','form_factor','smart_support_is','smart_is','serial_number','power_on_hours_and_msec','airflow_temperature_cel','temperature_celsius','overall_health','critical_warning', 'data_units_read', 'data_units_written', 'pci_vender_subsystem_id', 'ieee_oui_id', 'firmware_updates', 'optional_admin_commands', 'optional_nvm_commands', 'max_data_transfer_size');

        // Get index ID
        $disk_id = (count($plist) -1 );
        
     // Parse data for each disk
     while ($disk_id > -1) {

        // Traverse the xml with translations
        foreach ($translate as $search => $field) {
                // If key is empty
            if ( ! isset($plist[$disk_id][$search])) {
                    $this->$field = null;
                // If key has blank value, null it in the db
            } else if ( $plist[$disk_id][$search] == "") {
                $this->$field = null;
                
                // Key is set
            } else {
                if ($search == "Head_Flying_Hours" && stripos($plist[$disk_id][$search], 'h') !== false) {

                    $headhours = explode("h+",$plist[$disk_id][$search]);
                    $this->$field = $headhours[0];

                    // If key is a string save it to the object
                } else if ( in_array($field, $strings)) {  
                    $this->$field = $plist[$disk_id][$search];

                    // If key is not a string save it to the object
                } else  {  
                    $this->$field = intval(preg_replace("/[^0-9]/", "", $plist[$disk_id][$search]));
                }
            }
        }
         
         //timestamp added by the server
         $this->timestamp = time();
         
         $this->id = '';
         $this->create(); 
         $disk_id--;
     }
        
		$this->save();
    }
}
