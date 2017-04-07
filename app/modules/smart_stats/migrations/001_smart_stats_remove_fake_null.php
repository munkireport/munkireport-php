<?php

// Remove fake nulls and set them to NULL

class Migration_smart_stats_remove_fake_null extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'smart_stats';
    }

    public function up()
    {
        // Set Nulls
         foreach (array('error_poh','timestamp','raw_read_error_rate','throughput_performance','spin_up_time','start_stop_count','reallocated_sector_ct','read_channel_margin','seek_error_rate','seek_time_performance','power_on_hours','spin_retry_count','calibration_retry_count','power_cycle_count','read_soft_error_rate','program_fail_count_chip','erase_fail_count_chip','wear_leveling_count','used_rsvd_blk_cnt_chip','used_rsvd_blk_cnt_tot','unused_rsvd_blk_cnt_tot','program_fail_cnt_total','erase_fail_count_total','runtime_bad_block','endtoend_error','reported_uncorrect','command_timeout','high_fly_writes','gsense_error_rate','poweroff_retract_count','load_cycle_count','hardware_ecc_recovered','reallocated_event_count','current_pending_sector','offline_uncorrectable','udma_crc_error_count','multi_zone_error_rate','soft_read_error_rate','data_address_mark_errs','run_out_cancel','soft_ecc_correction','thermal_asperity_rate','flying_height','spin_high_current','spin_buzz','offline_seek_performnce','disk_shift','loaded_hours','load_retry_count','load_friction','loadin_time','torqamp_count','head_amplitude','available_reservd_space','media_wearout_indicator','head_flying_hours','total_lbas_written','total_lbas_read','read_error_retry_rate','free_fall_sensor','host_reads_mib','host_writes_mib','grown_failing_block_ct','unexpect_power_loss_ct','non4k_aligned_access','sata_iface_downshift','factory_bad_block_ct','percent_lifetime_used','write_error_rate','success_rain_recov_cnt','total_host_sector_write','host_program_page_count','bckgnd_program_page_cnt','perc_rated_life_used','reallocate_nand_blk_cnt','ave_blockerase_count','Unused_Reserve_NAND_Blk','sata_interfac_downshift','ssd_life_left','life_curve_status','supercap_health','lifetime_writes_gib','lifetime_reads_gib','uncorrectable_error_cnt','ecc_error_eate','crc_error_count','supercap_status','exception_mode_status','por_recovery_count','total_reads_gib','total_writes_gib','thermal_throttle','perc_writeerase_count','perc_avail_resrvd_space','perc_writeerase_ct_bc','avg_writeerase_count','sata_phy_error') as $item)
        {    
            $sql = 'UPDATE smart_stats 
            SET '.$item.' = NULL
            WHERE '.$item.' = -9876543 OR '.$item.' = -9876540';
            $this->exec($sql);
        }   
    }

    public function down()
    {
        throw new Exception("Can't go back", 1);
    }
}
