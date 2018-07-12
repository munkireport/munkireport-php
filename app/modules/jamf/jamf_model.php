<?php
class Jamf_model extends \Model
{
    
    protected $error = '';
    protected $module_dir;
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'jamf'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['jamf_id'] = 0;
        $this->rs['name'] = '';
        $this->rs['mac_address'] = '';
        $this->rs['alt_mac_address'] = '';
        $this->rs['ip_address'] = '';
        $this->rs['last_reported_ip'] = '';
        $this->rs['jamf_version'] = '';
        $this->rs['barcode_1'] = '';
        $this->rs['barcode_2'] = '';
        $this->rs['asset_tag'] = '';
        $this->rs['managed'] = 0;
        $this->rs['management_username'] = '';
        $this->rs['management_password_sha256'] = '';
        $this->rs['mdm_capable'] = 0;
        $this->rs['mdm_capable_users'] = '';
        $this->rs['user_approved_enrollment'] = 0;
        $this->rs['user_approved_mdm'] = 0;
        $this->rs['enrolled_via_dep'] = 0;
        $this->rs['report_date_epoch'] = 0;
        $this->rs['last_contact_time_epoch'] = 0;
        $this->rs['initial_entry_date_epoch'] = 0;
        $this->rs['last_cloud_backup_date_epoch'] = 0;
        $this->rs['last_enrolled_date_epoch'] = 0;
        $this->rs['distribution_point'] = '';
        $this->rs['sus'] = '';
        $this->rs['netboot_server'] = '';
        $this->rs['site_id'] = 0;
        $this->rs['site_name'] = '';
        $this->rs['udid'] = '';
        $this->rs['disable_automatic_login'] = 0;
        $this->rs['itunes_store_account_is_active'] = 0;
        $this->rs['username'] = 0;
        $this->rs['realname'] = '';
        $this->rs['email_address'] = '';
        $this->rs['position'] = '';
        $this->rs['phone'] = '';
        $this->rs['department'] = '';
        $this->rs['building'] = '';
        $this->rs['room'] = '';
        $this->rs['is_purchased'] = 0;
        $this->rs['is_leased'] = 0;
        $this->rs['po_number'] = '';
        $this->rs['vendor'] = '';
        $this->rs['peripherals'] = '';
        $this->rs['applecare_id'] = '';
        $this->rs['purchase_price'] = '';
        $this->rs['purchasing_account'] = '';
        $this->rs['po_date_epoch'] = 0;
        $this->rs['warranty_expires_epoch'] = 0;
        $this->rs['lease_expires_epoch'] = 0;
        $this->rs['life_expectancy'] = 0;
        $this->rs['comands_completed'] = 0;
        $this->rs['comands_pending'] = 0;
        $this->rs['comands_failed'] = 0;
        $this->rs['purchasing_contact'] = '';
        $this->rs['master_password_set'] = 0;
        $this->rs['ble_capable'] = 0;
        $this->rs['available_ram_slots'] = 0;
        $this->rs['battery_capacity'] = 0;
        $this->rs['bus_speed'] = 0;
        $this->rs['cache_size'] = 0;
        $this->rs['number_cores'] = 0;
        $this->rs['number_processors'] = 0;
        $this->rs['processor_speed'] = 0;
        $this->rs['total_ram'] = 0;
        $this->rs['xprotect_version'] = '';
        $this->rs['gatekeeper_status'] = '';
        $this->rs['active_directory_status'] = '';
        $this->rs['boot_rom'] = '';
        $this->rs['disk_encryption_configuration'] = '';
        $this->rs['model'] = '';
        $this->rs['model_identifier'] = '';
        $this->rs['nic_speed'] = '';
        $this->rs['optical_drive'] = '';
        $this->rs['os_build'] = '';
        $this->rs['os_version'] = '';
        $this->rs['processor_architecture'] = '';
        $this->rs['processor_type'] = '';
        $this->rs['sip_status'] = '';
        $this->rs['smc_version'] = '';
        $this->rs['institutional_recovery_key'] = '';
        $this->rs['filevault_2_users'] = '';
        $this->rs['attachments'] = '';
        $this->rs['storage'] = '';
        $this->rs['applications'] = '';
        $this->rs['mapped_printers'] = '';
        $this->rs['unix_executables'] = '';
        $this->rs['certificates'] = '';
        $this->rs['licensed_software'] = '';
        $this->rs['cached_by_casper'] = '';
        $this->rs['installed_by_casper'] = '';
        $this->rs['installed_by_installer_swu'] = '';
        $this->rs['available_software_updates'] = '';
        $this->rs['running_services'] = '';
        $this->rs['extension_attributes'] = '';
        $this->rs['computer_group_memberships'] = '';
        $this->rs['local_accounts'] = '';
        $this->rs['user_inventories'] = '';
        $this->rs['configuration_profiles'] = '';
        $this->rs['policies_management'] = '';
        $this->rs['ebooks_management'] = '';
        $this->rs['mac_app_store_apps_management'] = '';
        $this->rs['managed_preference_profiles_management'] = '';
        $this->rs['restricted_software_management'] = '';
        $this->rs['smart_groups_management'] = '';
        $this->rs['static_groups_management'] = '';
        $this->rs['patch_reporting_software_titles_management'] = '';
        $this->rs['patch_policies_management'] = '';
        $this->rs['computer_usage_logs_history'] = '';
        $this->rs['audits_history'] = '';
        $this->rs['policy_logs_history'] = '';
        $this->rs['casper_remote_logs_history'] = '';
        $this->rs['screen_sharing_logs_history'] = '';
        $this->rs['casper_imaging_logs_history'] = '';
        $this->rs['commands_history'] = '';
        $this->rs['user_location_history'] = '';
        $this->rs['mac_app_store_applications_history'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial_number = $serial;
        
        $this->module_dir = dirname(__FILE__);
    }
    
    /**
    * Get XProtect version for widget
    *
    **/
    public function get_xprotect_version()
    {
        $sql = "SELECT COUNT(CASE WHEN xprotect_version <> '' AND xprotect_version IS NOT NULL THEN 1 END) AS count, xprotect_version 
                FROM jamf
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY xprotect_version
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->xprotect_version = $obj->xprotect_version ? $obj->xprotect_version : 'Unknown';
                $out[] = $obj;
            }
        }
        
        return $out;
    }
  
    /**
    * Get Jamf version for widget
    *
    **/
    public function get_jamf_version()
    {
        $sql = "SELECT COUNT(CASE WHEN jamf_version <> '' AND jamf_version IS NOT NULL THEN 1 END) AS count, jamf_version 
                FROM jamf
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY jamf_version
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->jamf_version = $obj->jamf_version ? $obj->jamf_version : 'Unknown';
                $out[] = $obj;
            }
        }
        
        return $out;
    }
  
    /**
    * Get departments for widget
    *
    **/
    public function get_jamf_departments()
    {
        $sql = "SELECT COUNT(CASE WHEN department <> '' AND department IS NOT NULL THEN 1 END) AS count, department
                FROM jamf
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY department
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->department = $obj->department ? $obj->department : 'Unknown';
                $out[] = $obj;
            }
        }
        
        return $out;
    }
  
    /**
    * Get buildings for widget
    *
    **/
    public function get_jamf_buildings()
    {
        $sql = "SELECT COUNT(CASE WHEN building <> '' AND building IS NOT NULL THEN 1 END) AS count, building
                FROM jamf
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY building
                ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->building = $obj->building ? $obj->building : 'Unknown';
                $out[] = $obj;
            }
        }
        
        return $out;
    }
  
    /**
    * Get Jamf data
    *
    * @return void
    * @author tuxudo
    **/
    public function run_jamf_stats()
    {
        // Check if we should enable Jamf lookup
        if (conf('jamf_enable')) {
            // Load Jamf helper
            require_once($this->module_dir.'/lib/jamf_helper.php');
            $jamf_helper = new munkireport\module\jamf\jamf_helper;
            $jamf_helper->pull_jamf_data($this);
            // ^^ Comment and uncomment to turn off and on
        }
         
        return $this;
    }
    
    /**
    * Process method, is called by the client
    *
    * @return void
    * @author tuxudo
    **/
    public function process()
    {
        $this->run_jamf_stats();
    }
}
