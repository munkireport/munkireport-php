<?php

namespace munkireport\module\jamf;

class Jamf_helper
{
    /**
     *
     * @param object Jamf model instance
     * @author tuxudo
     **/
    public function pull_jamf_data(&$Jamf_model)
    {        
        // Error message
        $error = '';
        
        // Trim off any slashes on the right
        $jamf_server = rtrim(conf('jamf_server'), '/');
        
        // Get computer data from Jamf
        $url = "{$jamf_server}/JSSResource/computers/serialnumber/{$Jamf_model->serial_number}";
        $jamf_computer_result = $this->get_jamf_url($url);
        
        if(! $jamf_computer_result){
            throw new Exception("No data received from Jamf server", 1);
        }
            
        // Process computer data
        $json = json_decode($jamf_computer_result);
        
        if( ! $json){
            $error = 'Machine not found in Jamf!';
            return $error;
        }
        
        // Transpose Jamf API output into Jamf model
        // General section 
        $Jamf_model->jamf_id = $json->computer->general->id;
        $Jamf_model->name = $json->computer->general->name;
        $Jamf_model->mac_address = $json->computer->general->mac_address;
        $Jamf_model->alt_mac_address = $json->computer->general->alt_mac_address;
        $Jamf_model->ip_address = $json->computer->general->ip_address;
        $Jamf_model->last_reported_ip = $json->computer->general->last_reported_ip;
        $Jamf_model->jamf_version = $json->computer->general->jamf_version;
        $Jamf_model->barcode_1 = $json->computer->general->barcode_1;
        $Jamf_model->barcode_2 = $json->computer->general->barcode_2;
        $Jamf_model->asset_tag = $json->computer->general->asset_tag;
        $Jamf_model->udid = $json->computer->general->udid;

        if (isset($json->computer->general->remote_management)) {
            $Jamf_model->managed = +$json->computer->general->remote_management->managed;
            $Jamf_model->management_username = $json->computer->general->remote_management->management_username;
            $Jamf_model->management_password_sha256 = $json->computer->general->remote_management->management_password_sha256;
        } else {
            $Jamf_model->managed = null;
            $Jamf_model->management_username = "";
            $Jamf_model->management_password_sha256 = "";
        }
        
        $Jamf_model->mdm_capable = +$json->computer->general->mdm_capable;
        
        if (isset($json->computer->general->mdm_capable_users->mdm_capable_user)) {
            $Jamf_model->mdm_capable_users = $json->computer->general->mdm_capable_users->mdm_capable_user;
        } else {
            $Jamf_model->mdm_capable_users = "";
        }
        
        if (isset($json->computer->general->management_status)) {
            if (isset($json->computer->general->management_status->user_approved_enrollment)) {
                $Jamf_model->user_approved_enrollment = +$json->computer->general->management_status->user_approved_enrollment;
            } else {
                $Jamf_model->user_approved_enrollment = null;
            }
            if (isset($json->computer->general->management_status->user_approved_mdm)) {
                $Jamf_model->user_approved_mdm = +$json->computer->general->management_status->user_approved_mdm;
            } else {
                $Jamf_model->user_approved_mdm = null;
            }
            $Jamf_model->enrolled_via_dep = +$json->computer->general->management_status->enrolled_via_dep;
        } else {
            $Jamf_model->enrolled_via_dep = null;
            $Jamf_model->user_approved_mdm = null;
            $Jamf_model->user_approved_enrollment = null;
        }
                
        if (+$json->computer->general->report_date_epoch != 0){
            $Jamf_model->report_date_epoch = $json->computer->general->report_date_epoch;
        } else {
            $Jamf_model->report_date_epoch = null;
        }
        
        if (+$json->computer->general->last_contact_time_epoch != 0){
            $Jamf_model->last_contact_time_epoch = $json->computer->general->last_contact_time_epoch;
        } else {
            $Jamf_model->last_contact_time_epoch = null;
        }
        
        if (+$json->computer->general->initial_entry_date_epoch != 0){
            $Jamf_model->initial_entry_date_epoch = $json->computer->general->initial_entry_date_epoch;
        } else {
            $Jamf_model->initial_entry_date_epoch = null;
        }
        
        if (+$json->computer->general->last_cloud_backup_date_epoch != 0){
            $Jamf_model->last_cloud_backup_date_epoch = $json->computer->general->last_cloud_backup_date_epoch;
        } else {
            $Jamf_model->last_cloud_backup_date_epoch = null;
        }
        
        if (+$json->computer->general->last_enrolled_date_epoch != 0){
            $Jamf_model->last_enrolled_date_epoch = $json->computer->general->last_enrolled_date_epoch;
        } else {
            $Jamf_model->last_enrolled_date_epoch = null;
        }
        
        $Jamf_model->distribution_point = $json->computer->general->distribution_point;
        $Jamf_model->sus = $json->computer->general->sus;
        $Jamf_model->netboot_server = $json->computer->general->netboot_server;
        $Jamf_model->itunes_store_account_is_active = +$json->computer->general->itunes_store_account_is_active;

        if (isset($json->computer->general->site)) {
            $Jamf_model->site_id = $json->computer->general->site->id;
            $Jamf_model->site_name = $json->computer->general->site->name;
        } else {
            $Jamf_model->site_id = "";
            $Jamf_model->site_name = "";
        }
                
        // Location section
        $Jamf_model->username = $json->computer->location->username;
        $Jamf_model->realname = $json->computer->location->realname;
        $Jamf_model->email_address = $json->computer->location->email_address;
        $Jamf_model->position = $json->computer->location->position;
        $Jamf_model->phone = $json->computer->location->phone;
        $Jamf_model->department = $json->computer->location->department;
        $Jamf_model->building = $json->computer->location->building;
        $Jamf_model->room = $json->computer->location->room;
        
        // Purchasing section
        $Jamf_model->is_purchased = +$json->computer->purchasing->is_purchased;
        $Jamf_model->is_leased = +$json->computer->purchasing->is_leased;
        $Jamf_model->po_number = $json->computer->purchasing->po_number;
        $Jamf_model->vendor = $json->computer->purchasing->vendor;
        $Jamf_model->attachments = json_encode($json->computer->purchasing->attachments); // Encode the attachments array for processing by the client tab
        $Jamf_model->applecare_id = $json->computer->purchasing->applecare_id;
        $Jamf_model->purchase_price = $json->computer->purchasing->purchase_price;
        $Jamf_model->purchasing_account = $json->computer->purchasing->purchasing_account;
        $Jamf_model->purchasing_contact = $json->computer->purchasing->purchasing_contact;

        if (+$json->computer->purchasing->po_date_epoch != 0){
            $Jamf_model->po_date_epoch = $json->computer->purchasing->po_date_epoch;
        } else {
            $Jamf_model->po_date_epoch = null;
        }
        
        if (+$json->computer->purchasing->warranty_expires_epoch != 0){
            $Jamf_model->warranty_expires_epoch = $json->computer->purchasing->warranty_expires_epoch;
        } else {
            $Jamf_model->warranty_expires_epoch = null;
        }
        
        if (+$json->computer->purchasing->lease_expires_epoch != 0){
            $Jamf_model->lease_expires_epoch = $json->computer->purchasing->lease_expires_epoch;
        } else {
            $Jamf_model->lease_expires_epoch = null;
        }   
        
        if (+$json->computer->purchasing->life_expectancy != 0){
            $Jamf_model->life_expectancy = $json->computer->purchasing->life_expectancy;
        } else {
            $Jamf_model->life_expectancy = null;
        }
                
        // Hardware section
        $Jamf_model->ble_capable = +$json->computer->hardware->ble_capable;
        $Jamf_model->active_directory_status = $json->computer->hardware->active_directory_status;
        $Jamf_model->available_ram_slots = $json->computer->hardware->available_ram_slots;
        $Jamf_model->boot_rom = $json->computer->hardware->boot_rom;
        
        if ($json->computer->hardware->battery_capacity >= 0){
            $Jamf_model->battery_capacity = $json->computer->hardware->battery_capacity;
        } else {
            $Jamf_model->battery_capacity = null;
        }
        
        if ($json->computer->hardware->bus_speed != 0){
            $Jamf_model->bus_speed = $json->computer->hardware->bus_speed;
        } else {
            $Jamf_model->bus_speed = null;
        }  
        
        $Jamf_model->cache_size = $json->computer->hardware->cache_size;
        $Jamf_model->disk_encryption_configuration = $json->computer->hardware->disk_encryption_configuration;
        $Jamf_model->filevault_2_users = implode(", ", $json->computer->hardware->filevault2_users);
        $Jamf_model->gatekeeper_status = $json->computer->hardware->gatekeeper_status;
        $Jamf_model->institutional_recovery_key = $json->computer->hardware->institutional_recovery_key;
        $Jamf_model->mapped_printers = json_encode($json->computer->hardware->mapped_printers); // Encode the mapped_printers array for processing by the client tab
        $Jamf_model->applications = json_encode($json->computer->software->applications); // Encode the applications array for processing by the client tab
        $Jamf_model->master_password_set = +$json->computer->hardware->master_password_set;
        $Jamf_model->model = $json->computer->hardware->model;
        $Jamf_model->model_identifier = $json->computer->hardware->model_identifier;
        $Jamf_model->nic_speed = $json->computer->hardware->nic_speed;
        $Jamf_model->number_cores = $json->computer->hardware->number_cores;
        $Jamf_model->number_processors = $json->computer->hardware->number_processors;
        $Jamf_model->optical_drive = $json->computer->hardware->optical_drive;
        $Jamf_model->os_build = $json->computer->hardware->os_build;
        $Jamf_model->os_version = $json->computer->hardware->os_version;
        $Jamf_model->processor_architecture = $json->computer->hardware->processor_architecture;
        $Jamf_model->processor_speed = $json->computer->hardware->processor_speed;
        $Jamf_model->processor_type = $json->computer->hardware->processor_type;
        $Jamf_model->sip_status = $json->computer->hardware->sip_status;
        $Jamf_model->smc_version = $json->computer->hardware->smc_version;
        $Jamf_model->total_ram = $json->computer->hardware->total_ram;
        $Jamf_model->xprotect_version = $json->computer->hardware->xprotect_version;
        
        // Software section
        $Jamf_model->unix_executables = implode(",", $json->computer->software->unix_executables);
        $Jamf_model->licensed_software = implode(",", $json->computer->software->licensed_software);
        $Jamf_model->available_software_updates = implode(",", $json->computer->software->available_software_updates);
        $Jamf_model->running_services = implode(",", $json->computer->software->running_services);
        
        if ($json->computer->software->cached_by_casper != 0){
            $Jamf_model->cached_by_casper = implode(",", $json->computer->software->cached_by_casper);
        } else {
            $Jamf_model->cached_by_casper = null;
        }
        
        if ($json->computer->software->installed_by_installer_swu != 0){
            $Jamf_model->installed_by_installer_swu = implode(",", $json->computer->software->installed_by_installer_swu);
        } else {
            $Jamf_model->installed_by_installer_swu = null;
        }
        
        if ($json->computer->software->installed_by_casper != 0){
            $Jamf_model->installed_by_casper = implode(",", $json->computer->software->installed_by_casper);
        } else {
            $Jamf_model->installed_by_casper = null;
        }
        
        // Extension attributes section
        if (isset($json->computer->extension_attributes)) {
            $Jamf_model->extension_attributes = json_encode($json->computer->extension_attributes); // Encode the extension_attributes array for processing by the client tab
        } else {
            $Jamf_model->extension_attributes = "[]";
        }
        
        // Peripherals section
        if (isset($json->computer->peripherals->peripherals)) {
            $Jamf_model->peripherals = json_encode($json->computer->peripherals->peripherals); // Encode the peripherals array for processing by the client tab
        } else {
            $Jamf_model->peripherals = "[]";
        }
        
        // Certificates section
        if (isset($json->computer->certificates)) {
            $Jamf_model->certificates = json_encode($json->computer->certificates); // Encode the certificates array for processing by the client tab
        } else {
            $Jamf_model->certificates = "[]";
        }
                
        // Groups accounts section
        $Jamf_model->computer_group_memberships = implode(", ", $json->computer->groups_accounts->computer_group_memberships);
        $Jamf_model->local_accounts = json_encode($json->computer->groups_accounts->local_accounts); // Encode the local_accounts array for processing by the client tab
        $Jamf_model->user_inventories = json_encode($json->computer->groups_accounts->user_inventories); // Encode the user_inventories array for processing by the client tab
        $Jamf_model->disable_automatic_login = +$json->computer->groups_accounts->user_inventories->disable_automatic_login;
        
        
        // Process Storage section separately as XML because of limitations (bug?) with JSON format
        // Get computer data from Jamf
        $url = "{$jamf_server}/JSSResource/computers/serialnumber/{$Jamf_model->serial_number}";
        $jamf_storage_xml_result = $this->get_jamf_url_xml($url);
        
        $xml = simplexml_load_string($jamf_storage_xml_result);
        $storage_array = []; // Create blank storage devices array
        foreach ($xml->hardware->storage->device as $device) {
            $partitions = []; // Create blank partitions array
            $device_array = []; // Create blank device array
            foreach ($device->partition as $partition) {
                array_push($partitions, $partition);
            }
            // Fill in drive details
            $device_array["partitions"] = $partitions;
            $device_array['disk'] = "$device->disk";
            $device_array['drive_capacity_mb'] = "$device->drive_capacity_mb";
            $device_array['model'] = "$device->model";
            $device_array['revision'] = "$device->revision";
            $device_array['serial_number'] = "$device->serial_number";
            $device_array['smart_status'] = "$device->smart_status";
            $device_array['partition_count'] = count($partitions);
            // Add drive to storage array
            $storage_array[] = $device_array;
        }
        $Jamf_model->storage = json_encode($storage_array); // Encode the storage array for processing by the client tab
        
        
        // Process Profiles section
        $profiles_array = [];
        foreach($json->computer->configuration_profiles as $profile){
            // Only get properties for user created profiles
            if (intval($profile->id) > 0){
                // Get profile data from Jamf
                $url = "{$jamf_server}/JSSResource/osxconfigurationprofiles/id/{$profile->id}";
                $jamf_profile_result = $this->get_jamf_url($url);

                // Process profile data
                $profile_json = json_decode($jamf_profile_result);

                $profile->uuid = $profile_json->os_x_configuration_profile->general->uuid;
                $profile->name = $profile_json->os_x_configuration_profile->general->name;
                $profile->description = $profile_json->os_x_configuration_profile->general->description;
                $profile->distribution_method = $profile_json->os_x_configuration_profile->general->distribution_method;
                $profile->level = $profile_json->os_x_configuration_profile->general->level;
                $profile->redeploy_on_update = $profile_json->os_x_configuration_profile->general->redeploy_on_update;
                $profile->payload = $profile_json->os_x_configuration_profile->general->payloads;
                $profile->user_removable = +$profile_json->os_x_configuration_profile->general->user_removable;

                array_push($profiles_array, $profile);
            }
        }
        
        $Jamf_model->configuration_profiles = json_encode($profiles_array); // Encode the profiles_array array for processing by the client tab
        
        // Get computer management data from Jamf
        $url = "{$jamf_server}/JSSResource/computermanagement/serialnumber/{$Jamf_model->serial_number}";
        $jamf_computermanagement_result = $this->get_jamf_url($url);
        
        // Process computer mangement data
        $json = json_decode($jamf_computermanagement_result);
        
        // Computer Management
        $Jamf_model->policy_logs_history = json_encode($json->computer_management->policies); // Encode the policies array for processing by the client tab
        $Jamf_model->ebooks_management = json_encode($json->computer_management->ebooks); // Encode the ebooks array for processing by the client tab
        $Jamf_model->mac_app_store_apps_management = json_encode($json->computer_management->mac_app_store_apps); // Encode the mac_app_store_apps array for processing by the client tab
        $Jamf_model->managed_preference_profiles_management = json_encode($json->computer_management->managed_preference_profiles); // Encode the managed_preference_profiles array for processing by the client tab
        $Jamf_model->restricted_software_management = json_encode($json->computer_management->restricted_software); // Encode the restricted_software array for processing by the client tab
        $Jamf_model->policies_management = json_encode($json->computer_management->policies); // Encode the policies array for processing by the client tab
        $Jamf_model->smart_groups_management = json_encode($json->computer_management->smart_groups); // Encode the smart_groups array for processing by the client tab
        $Jamf_model->static_groups_management = json_encode($json->computer_management->static_groups); // Encode the static_groups array for processing by the client tab
        
        if (isset($json->computer_management->patch_reporting)) {
            $Jamf_model->patch_reporting_software_titles_management = json_encode($json->computer_management->patch_reporting->patch_reporting_software_titles);
            $Jamf_model->patch_policies_management = json_encode($json->computer_management->patch_reporting->patch_policies);
        } else {
            $Jamf_model->patch_reporting_software_titles_management = "[]";
            $Jamf_model->patch_policies_management = "[]";
        }
        
        // Get computer history data from Jamf
        $url = "{$jamf_server}/JSSResource/computerhistory/serialnumber/{$Jamf_model->serial_number}";
        $jamf_computerhistory_result = $this->get_jamf_url($url);
            
        // Process computer history data
        $json = json_decode($jamf_computerhistory_result);
        
        // Computer history section
        $Jamf_model->computer_usage_logs_history = json_encode($json->computer_history->computer_usage_logs); // Encode the computer_usage_logs array for processing by the client tab
        $Jamf_model->audits_history = json_encode($json->computer_history->audits); // Encode the audits array for processing by the client tab
        $Jamf_model->policy_logs_history = json_encode($json->computer_history->policy_logs); // Encode the policy_logs array for processing by the client tab
        $Jamf_model->casper_remote_logs_history = json_encode($json->computer_history->casper_remote_logs); // Encode the casper_remote_logs array for processing by the client tab
        $Jamf_model->screen_sharing_logs_history = json_encode($json->computer_history->screen_sharing_logs); // Encode the screen_sharing_logs array for processing by the client tab
        $Jamf_model->casper_imaging_logs_history = json_encode($json->computer_history->casper_imaging_logs); // Encode the casper_imaging_logs array for processing by the client tab
        $Jamf_model->commands_history = json_encode($json->computer_history->commands); // Encode the commands array for processing by the client tab
        $Jamf_model->user_location_history = json_encode($json->computer_history->user_location); // Encode the user_location array for processing by the client tab
        $Jamf_model->mac_app_store_applications_history = json_encode($json->computer_history->mac_app_store_applications); // Encode the mac_app_store_applications array for processing by the client tab
        
        $Jamf_model->comands_completed = count($json->computer_history->commands->completed); // Count completed commands
        $Jamf_model->comands_pending = count($json->computer_history->commands->pending); // Count pending commands
        $Jamf_model->comands_failed = count($json->computer_history->commands->failed); // Count failed commands

        // Save the data
        $Jamf_model->save();
        $error = 'Jamf data processed';
        return $error;
    }
    
    
    /**
     * Retrieve url
     *
     * @return JSON object if successful, FALSE if failed
     * @author n8felton, tweaked for Jamf by Tuxudo
     *
     **/
    public function get_jamf_url($url)
    {
        $jamf_username = conf('jamf_username');
        $jamf_password = conf('jamf_password');
        if(conf('jamf_verify_ssl') == FALSE || $jamf_verify_ssl == "false" || $jamf_verify_ssl == "FALSE" || $jamf_verify_ssl == "0" || $jamf_verify_ssl == 0){
            $jamf_verify_ssl = 0;
        } else {
            $jamf_verify_ssl = 1;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout of 5 seconds
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$jamf_username:$jamf_password");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $jamf_verify_ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $jamf_verify_ssl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
        $return = curl_exec($ch);
        return $return;
    }

    /**
     * Retrieve url as XML
     *
     * @return JSON object if successful, FALSE if failed
     * @author n8felton, tweaked for Jamf by Tuxudo
     *
     **/
    public function get_jamf_url_xml($url)
    {
        $jamf_username = conf('jamf_username');
        $jamf_password = conf('jamf_password');
        if(conf('jamf_verify_ssl') == FALSE || $jamf_verify_ssl == "false" || $jamf_verify_ssl == "FALSE" || $jamf_verify_ssl == "0" || $jamf_verify_ssl == 0){
            $jamf_verify_ssl = 0;
        } else {
            $jamf_verify_ssl = 1;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout of 5 seconds
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$jamf_username:$jamf_password");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $jamf_verify_ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $jamf_verify_ssl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: document/xml"));
        $return = curl_exec($ch);
        return $return;
    }
}
