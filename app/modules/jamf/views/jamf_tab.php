<h2>Jamf  <a data-i18n="jamf.recheck" class="btn btn-default btn-xs" href="<?php echo url('module/jamf/recheck_jamf/' . $serial_number);?>"></a>  <span id="jamf_view_in"></span></h2>

<div id="jamf-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="jamf-view" class="row hide">
    <div class="col-md-12">

        <!--Top nav tabs-->
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#jamf-inventory" data-i18n="jamf.inventory"></a></li>
          <li><a data-toggle="tab" href="#jamf-management" data-i18n="jamf.management"></a></li>
          <li><a data-toggle="tab" href="#jamf-history" data-i18n="jamf.history"></a></li>
        </ul>

        <!--Top tabs content-->
        <div class="tab-content">
          <!--Inventory tab-->
          <div id="jamf-inventory" class="tab-pane in active">
            <!--Inventory side tabs-->
            <ul class="nav nav-tabs jamf-left">
              <li class="active" id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-General" id="jamf_general_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Hardware" id="jamf_hardware_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-OperatingSystem" id="jamf_operatingsystem_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-UserLocation" id="jamf_userlocation_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Security" id="jamf_security_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Purchasing" id="jamf_purchasing_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Storage" id="jamf_storage_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-DiskEncryption" id="jamf_diskencryption_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Applications" id="jamf_applications_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Profiles" id="jamf_profiles_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Certificates" id="jamf_certificates_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-PackageReceipts" id="jamf_packagereceipts_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-LocalUserAccounts" id="jamf_localuseraccounts_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Printers" id="jamf_printers_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Services" id="jamf_services_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Attachments" id="jamf_attachments_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ExtensionAttributes" id="jamf_extension_attributes_button"></a></li>
            </ul>  
            <!--Inventory side tabs content-->
            <div class="tab-content jamf-tab-content">
                <!--General tab content-->
                <div id="Jamf-General" class="tab-pane in active">
                    <h4 data-i18n="jamf.general"></h4>
                    <!--General table-->
                    <table class="table table-striped">
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.name"></th>
                            <td class="jamf-table-left" id="jamf_name"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.report_date_epoch"></th>
                            <td class="jamf-table-left" id="jamf_report_date_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.last_contact_time_epoch"></th>
                            <td class="jamf-table-left" id="jamf_last_contact_time_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.initial_entry_date_epoch"></th>
                            <td class="jamf-table-left" id="jamf_initial_entry_date_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.ip_address"></th>
                            <td class="jamf-table-left" id="jamf_ip_address"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.last_reported_ip"></th>
                            <td class="jamf-table-left" id="jamf_last_reported_ip"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.jamf_version"></th>
                            <td class="jamf-table-left" id="jamf_version"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.managed"></th>
                            <td class="jamf-table-left" id="jamf_managed"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.last_cloud_backup_date_epoch"></th>
                            <td class="jamf-table-left" id="jamf_last_cloud_backup_date_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.last_enrolled_date_epoch"></th>
                            <td class="jamf-table-left" id="jamf_last_enrolled_date_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.mdm_capable"></th>
                            <td class="jamf-table-left" id="jamf_mdm_capable"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.enrolled_via_dep"></th>
                            <td class="jamf-table-left" id="jamf_enrolled_via_dep"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.user_approved_mdm"></th>
                            <td class="jamf-table-left" id="jamf_user_approved_mdm"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.user_approved_enrollment"></th>
                            <td class="jamf-table-left" id="jamf_user_approved_enrollment"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.mdm_capable_users"></th>
                            <td class="jamf-table-left" id="jamf_mdm_capable_users"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.jamf_id"></th>
                            <td class="jamf-table-left" id="jamf_id"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.asset_tag"></th>
                            <td class="jamf-table-left" id="jamf_asset_tag"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.barcode_1"></th>
                            <td class="jamf-table-left" id="jamf_barcode_1"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.barcode_2"></th>
                            <td class="jamf-table-left" id="jamf_barcode_2"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.ble_capable"></th>
                            <td class="jamf-table-left" id="jamf_ble_capable"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.itunes_store_account_is_active"></th>
                            <td class="jamf-table-left" id="jamf_itunes_store_account_is_active"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.distribution_point"></th>
                            <td class="jamf-table-left" id="jamf_distribution_point"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.sus"></th>
                            <td class="jamf-table-left" id="jamf_sus"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.netboot_server"></th>
                            <td class="jamf-table-left" id="jamf_netboot_server"></td>
                        </tr>
                    </table>
                </div>
                <!--Hardware tab content-->
                <div id="Jamf-Hardware" class="tab-pane">
                    <h4 data-i18n="hardware.hardware"></h4>
                    <!--Hardware table-->
                    <table class="table table-striped">
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.model"></th>
                            <td class="jamf-table-left" id="jamf_model"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.model_identifier"></th>
                            <td class="jamf-table-left" id="jamf_model_identifier"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.udid"></th>
                            <td class="jamf-table-left" id="jamf_udid"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="serial"></th>
                            <td class="jamf-table-left" id="jamf_serial_number"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.processor_speed"></th>
                            <td class="jamf-table-left" id="jamf_processor_speed"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.number_processors"></th>
                            <td class="jamf-table-left" id="jamf_number_processors"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.number_cores"></th>
                            <td class="jamf-table-left" id="jamf_number_cores"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.processor_type"></th>
                            <td class="jamf-table-left" id="jamf_processor_type"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.processor_architecture"></th>
                            <td class="jamf-table-left" id="jamf_processor_architecture"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.bus_speed"></th>
                            <td class="jamf-table-left" id="jamf_bus_speed"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.cache_size"></th>
                            <td class="jamf-table-left" id="jamf_cache_size"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.mac_address"></th>
                            <td class="jamf-table-left" id="jamf_mac_address"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.alt_mac_address"></th>
                            <td class="jamf-table-left" id="jamf_alt_mac_address"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.total_ram"></th>
                            <td class="jamf-table-left" id="jamf_total_ram"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.available_ram_slots"></th>
                            <td class="jamf-table-left" id="jamf_available_ram_slots"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.battery_capacity"></th>
                            <td class="jamf-table-left" id="jamf_battery_capacity"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.smc_version"></th>
                            <td class="jamf-table-left" id="jamf_smc_version"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.nic_speed"></th>
                            <td class="jamf-table-left" id="jamf_nic_speed"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.optical_drive"></th>
                            <td class="jamf-table-left" id="jamf_optical_drive"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.boot_rom"></th>
                            <td class="jamf-table-left" id="jamf_boot_rom"></td>
                        </tr>
                    </table>
                </div>
                <!--OperatingSystem tab content-->
                <div id="Jamf-OperatingSystem" class="tab-pane">
                  <h4 data-i18n="jamf.operatingsystem"></h4>
                  <!--OperatingSystem table-->
                    <table class="table table-striped">
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.os_version"></th>
                            <td class="jamf-table-left" id="jamf_os_version"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.os_build"></th>
                            <td class="jamf-table-left" id="jamf_os_build"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.active_directory_status"></th>
                            <td class="jamf-table-left" id="jamf_active_directory_status"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.master_password_set"></th>
                            <td class="jamf-table-left" id="jamf_master_password_set"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.filevault_2_users"></th>
                            <td class="jamf-table-left" id="jamf_filevault_2_users"></td>
                        </tr>
                    </table>
                </div>
                <!--UserLocation tab content-->
                <div id="Jamf-UserLocation" class="tab-pane">
                  <h4 data-i18n="jamf.userlocation"></h4>
                  <!--UserLocation table-->
                    <table class="table table-striped">
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.username"></th>
                            <td class="jamf-table-left" id="jamf_username"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.realname"></th>
                            <td class="jamf-table-left" id="jamf_realname"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.email_address"></th>
                            <td class="jamf-table-left" id="jamf_email_address"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.phone"></th>
                            <td class="jamf-table-left" id="jamf_phone"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.position"></th>
                            <td class="jamf-table-left" id="jamf_position"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.department"></th>
                            <td class="jamf-table-left" id="jamf_department"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.building"></th>
                            <td class="jamf-table-left" id="jamf_building"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.room"></th>
                            <td class="jamf-table-left" id="jamf_room"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right hide" id="jamf_site_name_head" data-i18n="jamf.site_name"></th>
                            <td class="jamf-table-left hide" id="jamf_site_name"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right hide" id="jamf_site_id_head" data-i18n="jamf.site_id"></th>
                            <td class="jamf-table-left hide" id="jamf_site_id"></td>
                        </tr>
                    </table>
                </div>  
                <!--Security tab content-->
                <div id="Jamf-Security" class="tab-pane">
                  <h4 data-i18n="jamf.security"></h4>
                  <!--Security table-->
                    <table class="table table-striped">
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.sip_status"></th>
                            <td class="jamf-table-left" id="jamf_sip_status"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.gatekeeper_status"></th>
                            <td class="jamf-table-left" id="jamf_gatekeeper_status"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.xprotect_version"></th>
                            <td class="jamf-table-left" id="jamf_xprotect_version"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.disable_automatic_login"></th>
                            <td class="jamf-table-left" id="jamf_disable_automatic_login"></td>
                        </tr>
                    </table>
                </div>
                <!--Purchasing tab content-->
                <div id="Jamf-Purchasing" class="tab-pane">
                  <h4 data-i18n="jamf.purchasing"></h4>
                  <!--Purchasing table-->
                    <table class="table table-striped">
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.purchased_or_leased"></th>
                            <td class="jamf-table-left" id="jamf_is_purchased"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.po_number"></th>
                            <td class="jamf-table-left" id="jamf_po_number"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.po_date_epoch"></th>
                            <td class="jamf-table-left" id="jamf_po_date_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.vendor"></th>
                            <td class="jamf-table-left" id="jamf_vendor"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.warranty_expires_epoch"></th>
                            <td class="jamf-table-left" id="jamf_warranty_expires_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.applecare_id"></th>
                            <td class="jamf-table-left" id="jamf_applecare_id"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.lease_expires_epoch"></th>
                            <td class="jamf-table-left" id="jamf_lease_expires_epoch"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.purchase_price"></th>
                            <td class="jamf-table-left" id="jamf_purchase_price"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.life_expectancy"></th>
                            <td class="jamf-table-left" id="jamf_life_expectancy"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.purchasing_account"></th>
                            <td class="jamf-table-left" id="jamf_purchasing_account"></td>
                        </tr>
                        <tr>
                            <th class="jamf-table-right" data-i18n="jamf.purchasing_contact"></th>
                            <td class="jamf-table-left" id="jamf_purchasing_contact"></td>
                        </tr>
                    </table>
                </div>
                <!--Storage tab content-->
                <div id="Jamf-Storage" class="tab-pane">
                  <!--Storage table-->
                    <div id="Jamf-Storage-Table"></div>
                </div>
                <!--DiskEncryption tab content-->
                <div id="Jamf-DiskEncryption" class="tab-pane">
                  <!--DiskEncryption table-->
                    <div id="Jamf-Storage-Encryption-Table"></div>
                </div>
                <!--Applications tab content-->
                <div id="Jamf-Applications" class="tab-pane">
                  <!--Applications table-->
                  <div id="Jamf-Applications-Table"></div>
                </div>
                <!--Profiles tab content-->
                <div id="Jamf-Profiles" class="tab-pane">
                  <!--Profiles table-->
                  <div id="Jamf-Profiles-Table"></div>
                </div>
                
                <!--Certificates tab content-->
                <div id="Jamf-Certificates" class="tab-pane">
                  <!--Certificates table-->
                  <div id="Jamf-Certificates-Table"></div>
                </div>
                <!--PackageReceipts tab content-->
                <div id="Jamf-PackageReceipts" class="tab-pane">
                    <h4 data-i18n="jamf.packagereceipts"></h4>
                    <!--PackageReceipts button group-->
                    <div class="btn-group btn-group-justified">
                        <a data-toggle="tab" class="btn btn-primary active" href="#Jamf-Packages-Installer-Table" id="jamf_packages_installer_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-Packages-JamfPro-Table" id="jamf_packages_jamfpro_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-Packages-Cached-Table" id="jamf_packages_cached_button"></a>
                    </div>
                    <br/>
                    <!--PackageReceipts tables-->
                    <div class="tab-content jamf-tab-content">
                        <div id="Jamf-Packages-Installer-Table" class="tab-pane in active"></div>
                        <div id="Jamf-Packages-JamfPro-Table" class="tab-pane"></div>
                        <div id="Jamf-Packages-Cached-Table" class="tab-pane"></div>
                    </div>
                </div>
                <!--LocalUserAccounts tab content-->
                <div id="Jamf-LocalUserAccounts" class="tab-pane">
                  <!--LocalUserAccounts table-->
                  <div id="Jamf-LocalUserAccounts-Table"></div>
                </div>
                <!--Printers tab content-->
                <div id="Jamf-Printers" class="tab-pane">
                  <!--Printers table-->
                  <div id="Jamf-Printers-Table"></div>
                </div>
                <!--Services tab content-->
                <div id="Jamf-Services" class="tab-pane">
                  <!--Services table-->
                    <div id="Jamf-Services-Table"></div>
                </div>
                <!--Attachments tab content-->
                <div id="Jamf-ExtensionAttributes" class="tab-pane">
                  <!--ExtensionAttributes table-->
                    <div id="Jamf-ExtensionAttributes-Table"></div>
                </div>
                <!--Attachments tab content-->
                <div id="Jamf-Attachments" class="tab-pane">
                  <!--Attachments table-->
                    <div id="Jamf-Attachments-Table"></div>
                </div> 
            </div>
          </div>      
          <!--Mangement tab-->
          <div id="jamf-management" class="tab-pane">
            <!--Mangement side tabs-->
            <ul class="nav nav-tabs jamf-left">
              <li class="active" id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ManagementCommands" id="jamf_managementcommands_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-Policies" id="jamf_policies_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-eBooks" id="jamf_ebooks_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-MacAppStoreApps" id="jamf_mac_apps_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ConfigurationProfiles" id="jamf_config_profiles_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ManagedPreferences" id="jamf_man_prefs_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-RestrictedSoftware" id="jamf_restricted_software_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ComputerGroups" id="jamf_computergroups_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-PatchMangement" id="jamf_patchmanagement_button"></a></li>
            </ul>
            <!--Mangement side tabs content-->
            <div class="tab-content jamf-tab-content">
                <!--ManagementCommands tab content-->
                <div id="Jamf-ManagementCommands" class="tab-pane in active">
                  <!--ManagementCommands table-->
                  <div id="Jamf-ManagementCommands-Table"></div>
                </div>
                <!--Policies tab content-->
                <div id="Jamf-Policies" class="tab-pane">
                  <!--Policies table-->
                  <div id="Jamf-Policies-Table"></div>
                </div>
                <!--eBooks tab content-->
                <div id="Jamf-eBooks" class="tab-pane">
                  <!--eBooks table-->
                  <div id="Jamf-eBooks-Table"></div>
                </div>
                <!--MacAppStoreApps tab content-->
                <div id="Jamf-MacAppStoreApps" class="tab-pane">
                  <!--MacAppStoreApps table-->
                  <div id="Jamf-MacApps-Table"></div>
                </div>
                <!--ConfigurationProfiles tab content-->
                <div id="Jamf-ConfigurationProfiles" class="tab-pane">
                  <!--ConfigurationProfiles table-->
                  <div id="Jamf-ConfigurationProfiles-Table"></div>
                </div>
                <!--ManagedPreferences tab content-->
                <div id="Jamf-ManagedPreferences" class="tab-pane">
                  <!--ManagedPreferences table-->
                  <div id="Jamf-ManagedPreferences-Table"></div>
                </div>
                <!--RestrictedSoftware tab content-->
                <div id="Jamf-RestrictedSoftware" class="tab-pane">
                  <!--RestrictedSoftware table-->
                  <div id="Jamf-RestrictedSoftware-Table"></div>
                </div>
                <!--ComputerGroups tab content-->
                <div id="Jamf-ComputerGroups" class="tab-pane">
                    <!--ComputerGroups table-->
                    <h4 data-i18n="jamf.computergroups"></h4>
                    <!--ComputerGroups button group-->
                    <div class="btn-group btn-group-justified">
                        <a data-toggle="tab" class="btn btn-primary active" href="#Jamf-Groups-Smart-Table" id="jamf_groups_smart_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-Groups-Static-Table" id="jamf_groups_static_button"></a>
                    </div>
                    <br/>
                    <!--ComputerGroups tables-->
                    <div class="tab-content jamf-tab-content">
                        <div id="Jamf-Groups-Smart-Table" class="tab-pane in active"></div>
                        <div id="Jamf-Groups-Static-Table" class="tab-pane"></div>
                    </div>                
                </div>
                <!--PatchMangement tab content-->
                <div id="Jamf-PatchMangement" class="tab-pane">
                    <!--PatchMangement table-->
                    <h4 data-i18n="jamf.patch_management_logs_history"></h4>
                    <!--PatchMangement button group-->
                    <div class="btn-group btn-group-justified">
                        <a data-toggle="tab" class="btn btn-primary active" href="#Jamf-Software-Titles-Table" id="jamf_software_titles_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-Patch-Policies-Table" id="jamf_patch_policies_button"></a>
                    </div>
                    <br/>
                    <!--PatchMangement tables-->
                    <div class="tab-content jamf-tab-content">
                        <div id="Jamf-Software-Titles-Table" class="tab-pane in active"></div>
                        <div id="Jamf-Patch-Policies-Table" class="tab-pane"></div>
                    </div>                    
                </div>
            </div>
          </div>
          <!--History tab-->
          <div id="jamf-history" class="tab-pane">
            <!--History side tabs-->
            <ul class="nav nav-tabs jamf-left">
              <li class="active" id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ComputerUsage" id="jamf_computerusage_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-AuditLogs" id="jamf_auditlogs_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-PolicyLogs" id="jamf_policylogs_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-JamfRemote" id="jamf_jamfremote_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ScreenSharingLogs" id="jamf_screensharinglogs_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-JamfImaging" id="jamf_jamfimaging_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-ManagementHistory" id="jamf_managementhistory_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-UserLocationHistory" id="jamf_userlocationhistory_button"></a></li>
              <li id="jamf-button"><a class="jamf-tablink" data-toggle="tab" href="#Jamf-MacAppStoreAppsHistory" id="jamf_macappshistory_button"></a></li>
            </ul>
            <!--History side tabs content-->
            <div class="tab-content jamf-tab-content">
                <!--ComputerUsage tab content-->
                <div id="Jamf-ComputerUsage" class="tab-pane in active">
                  <!--ComputerUsage table-->
                  <div id="Jamf-ComputerUsage-Table"></div>
                </div>
                <!--AuditLogs tab content-->
                <div id="Jamf-AuditLogs" class="tab-pane">
                  <!--AuditLogs table-->
                  <div id="Jamf-AuditLogs-Table"></div>
                </div>
                <!--PolicyLogs tab content-->
                <div id="Jamf-PolicyLogs" class="tab-pane">
                  <!--PolicyLogs table-->
                  <div id="Jamf-PolicyLogs-Table"></div>
                </div>                
                <!--JamfRemote tab content-->
                <div id="Jamf-JamfRemote" class="tab-pane">
                  <!--JamfRemote table-->
                  <div id="Jamf-JamfRemote-Table"></div>
                </div>
                <!--ScreenSharingLogs tab content-->
                <div id="Jamf-ScreenSharingLogs" class="tab-pane">
                  <!--ScreenSharingLogs table-->
                  <div id="Jamf-ScreenSharingLogs-Table"></div>
                </div>
                <!--JamfImaging tab content-->
                <div id="Jamf-JamfImaging" class="tab-pane">
                  <!--JamfImaging table-->
                  <div id="Jamf-JamfImaging-Table"></div>
                </div>
                <!--ManagementHistory tab content-->
                <div id="Jamf-ManagementHistory" class="tab-pane">
                  <!--ManagementHistory table-->
                  <h4 data-i18n="jamf.managementhistory"></h4>
                    <!--ManagementHistory button group-->
                    <div class="btn-group btn-group-justified">
                        <a data-toggle="tab" class="btn btn-primary active" href="#Jamf-ManagementHistory-Completed-Table" id="jamf_managementhistory_completed_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-ManagementHistory-Pending-Table" id="jamf_managementhistory_pending_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-ManagementHistory-Failed-Table" id="jamf_managementhistory_failed_button"></a>
                    </div>
                    <br/>
                    <!--ManagementHistory tables-->
                    <div class="tab-content jamf-tab-content">
                        <div id="Jamf-ManagementHistory-Completed-Table" class="tab-pane in active"></div>
                        <div id="Jamf-ManagementHistory-Pending-Table" class="tab-pane"></div>
                        <div id="Jamf-ManagementHistory-Failed-Table" class="tab-pane"></div>
                    </div>
                </div>
                <!--UserLocationHistory tab content-->
                <div id="Jamf-UserLocationHistory" class="tab-pane">
                  <!--UserLocationHistory table-->
                  <div id="Jamf-UserLocationHistory-Table"></div>
                </div>
                <!--MacAppStoreAppsHistory tab content-->
                <div id="Jamf-MacAppStoreAppsHistory" class="tab-pane">
                  <!--MacAppStoreAppsHistory table-->
                  <h4 data-i18n="jamf.mac_app_store_applications_history"></h4>
                    <!--MacAppStoreAppsHistory button group-->
                    <div class="btn-group btn-group-justified">
                        <a data-toggle="tab" class="btn btn-primary active" href="#Jamf-MacApps-Installed-Table" id="jamf_macapps_installed_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-MacApps-Pending-Table" id="jamf_macapps_pending_button"></a>
                        <a data-toggle="tab" class="btn btn-primary" href="#Jamf-MacApps-Failed-Table" id="jamf_macapps_failed_button"></a>
                    </div>
                    <br/>
                    <!--MacAppStoreAppsHistory tables-->
                    <div class="tab-content jamf-tab-content">
                        <div id="Jamf-MacApps-Installed-Table" class="tab-pane in active"></div>
                        <div id="Jamf-MacApps-Pending-Table" class="tab-pane"></div>
                        <div id="Jamf-MacApps-Failed-Table" class="tab-pane"></div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

<script>    
$(document).on('appReady', function(e, lang) {

	// Get Jamf data
	$.getJSON( appUrl + '/module/jamf/get_data/' + serialNumber, function( data ) {
		if( ! data['jamf_id']){
			$('#jamf-msg').text(i18n.t('no_data'));
		}
		else{

			// Hide
			$('#jamf-msg').text('');
			$('#jamf-view').removeClass('hide');

            // Get the Jamf server address
            var jamf_server = "<?php echo rtrim(conf('jamf_server'), '/'); ?>";
            
            // Generate buttons and tabs
			$('#jamf_view_in').html('<a class="btn btn-default btn-xs" target="_blank" href="'+jamf_server+'/computers.html?id='+data['jamf_id']+'&o=r&v=inventory"> '+i18n.t("jamf.view_in_jamf")+'</a>'); // View in Jamf button
			$('#jamf_general_button').html('<i class="fa fa-info-circle"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.general")); // General tab
			$('#jamf_hardware_button').html('<i class="fa fa-desktop"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("hardware.hardware")); // Hardware tab
			$('#jamf_operatingsystem_button').html('<i class="fa fa-apple"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.operatingsystem")); // Operating System tab
			$('#jamf_userlocation_button').html('<i class="fa fa-building"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.userlocation")); // User Location tab
			$('#jamf_security_button').html('<i class="fa fa-lock"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.security")); // Security tab
			$('#jamf_purchasing_button').html('<i class="fa fa-money"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.purchasing")); // Purchasing tab
			$('#jamf_storage_button').html('<i class="fa fa-hdd-o"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.storage")+'&nbsp;&nbsp;<span id="jamf-storage-cnt" class="badge"></span>'); // Storage tab
			$('#jamf_diskencryption_button').html('<i class="fa fa-home"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.diskencryption")); // Disk Encryption tab
			$('#jamf_applications_button').html('<i class="fa fa-language"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.applications")+'&nbsp;&nbsp;<span id="jamf-apps-cnt" class="badge"></span>'); // Applications tab
			$('#jamf_profiles_button').html('<i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.profiles")+'&nbsp;&nbsp;<span id="jamf-profs-cnt" class="badge"></span>'); // Profiles tab
			$('#jamf_certificates_button').html('<i class="fa fa-certificate"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.certificates")+'&nbsp;&nbsp;<span id="jamf-certs-cnt" class="badge"></span>'); // Certificates tab
			$('#jamf_packagereceipts_button').html('<i class="fa fa-dropbox"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.packagereceipts")+'&nbsp;&nbsp;<span id="jamf-instpkgs-cnt" class="badge"></span>'); // Package Receipts tab
			$('#jamf_localuseraccounts_button').html('<i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.localuseraccounts")+'&nbsp;&nbsp;<span id="jamf-laccounts-cnt" class="badge"></span>'); // Local User Accounts tab
			$('#jamf_printers_button').html('<i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.printers")+'&nbsp;&nbsp;<span id="jamf-printers-cnt" class="badge"></span>'); // Printers tab
			$('#jamf_services_button').html('<i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.services")+'&nbsp;&nbsp;<span id="jamf-service-cnt" class="badge"></span>'); // Services tab
			$('#jamf_attachments_button').html('<i class="fa fa-paperclip"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.attachments")+'&nbsp;&nbsp;<span id="jamf-attach-cnt" class="badge"></span>'); // Attachments tab
			$('#jamf_extension_attributes_button').html('<i class="fa fa-puzzle-piece"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.extension_attributes")+'&nbsp;&nbsp;<span id="jamf-extensions-cnt" class="badge"></span>'); // Extension Attributes tab
			$('#jamf_managementcommands_button').html('<i class="fa fa-tachometer"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.managementcommands")); // Management Commands tab
			$('#jamf_policies_button').html('<i class="fa fa-window-restore"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.policies_management")+'&nbsp;&nbsp;<span id="jamf-policies-cnt" class="badge"></span>'); // Policies tab
			$('#jamf_ebooks_button').html('<i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.ebooks_management")+'&nbsp;&nbsp;<span id="jamf-ebooks-cnt" class="badge"></span>'); // eBooks tab
			$('#jamf_mac_apps_button').html('<i class="fa fa-caret-square-o-up"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.mac_app_store_applications_history")+'&nbsp;&nbsp;<span id="jamf-macapps-cnt" class="badge"></span>'); // Mac App Store tab
			$('#jamf_config_profiles_button').html('<i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.configuration_profiles")+'&nbsp;&nbsp;<span id="jamf-profs-cnt2" class="badge"></span>'); // Configuration Profiles tab
			$('#jamf_man_prefs_button').html('<i class="fa fa-sliders"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.managed_preference_profiles_management")+'&nbsp;&nbsp;<span id="jamf-manprefs-cnt" class="badge"></span>'); // Managed Preferences tab
			$('#jamf_restricted_software_button').html('<i class="fa fa-shield"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.restricted_software_management")+'&nbsp;&nbsp;<span id="jamf-restsoft-cnt" class="badge"></span>'); // Restricted Software tab
			$('#jamf_computergroups_button').html('<i class="fa fa-desktop"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.computergroups")); // Computer Groups tab
			$('#jamf_patchmanagement_button').html('<i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.patch_management_logs_history")); // Patch Management tab
            $('#jamf_computerusage_button').html('<i class="fa fa-desktop"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.computer_usage_logs_history")); // ComputerUsage tab
            $('#jamf_auditlogs_button').html('<i class="fa fa-clipboard"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.audits_history")); // AuditLogs tab
            $('#jamf_policylogs_button').html('<i class="fa fa-window-restore"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.policy_logs_history")); // PolicyLogs tab
            $('#jamf_jamfremote_button').html('<i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.casper_remote_logs_history")); // JamfRemote tab
            $('#jamf_screensharinglogs_button').html('<i class="fa fa-window-restore"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.screen_sharing_logs_history")); // ScreenSharingLogs tab
            $('#jamf_jamfimaging_button').html('<i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.casper_imaging_logs_history")); // JamfImaging tab
            $('#jamf_managementhistory_button').html('<i class="fa fa-tachometer"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.commands_history")); // ManagementHistory tab
            $('#jamf_userlocationhistory_button').html('<i class="fa fa-building"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.user_location_history")); // UserLocationHistory tab
            $('#jamf_macappshistory_button').html('<i class="fa fa-language"></i>&nbsp;&nbsp;&nbsp;'+i18n.t("jamf.mac_app_store_applications_history")); // MacAppStoreAppsHistory tab
            
            // Fix dates, after checking if date is set
            if ( data['last_contact_time_epoch'] !== null ){
                $('#jamf_last_contact_time_epoch').html(moment(parseInt(data['last_contact_time_epoch'])).format('llll')+' - '+moment(parseInt(data['last_contact_time_epoch'])).fromNow());
            }
            if ( data['initial_entry_date_epoch'] !== null ){
                $('#jamf_initial_entry_date_epoch').html(moment(parseInt(data['initial_entry_date_epoch'])).format('llll')+' - '+moment(parseInt(data['initial_entry_date_epoch'])).fromNow());
            }
            if ( data['po_date_epoch'] !== null ){
                $('#jamf_po_date_epoch').html(moment(parseInt(data['po_date_epoch'])).format('llll')+' - '+moment(parseInt(data['po_date_epoch'])).fromNow());
            }
            if ( data['report_date_epoch'] !== null ){
                $('#jamf_report_date_epoch').html(moment(parseInt(data['report_date_epoch'])).format('llll')+' - '+moment(parseInt(data['report_date_epoch'])).fromNow());
            }
            if ( data['last_enrolled_date_epoch'] !== null ){
                $('#jamf_last_enrolled_date_epoch').html(moment(parseInt(data['last_enrolled_date_epoch'])).format('llll')+' - '+moment(parseInt(data['last_enrolled_date_epoch'])).fromNow());
            }
            if ( data['warranty_expires_epoch'] !== null ){
                $('#jamf_warranty_expires_epoch').html(moment(parseInt(data['warranty_expires_epoch'])).format('llll')+' - '+moment(parseInt(data['warranty_expires_epoch'])).fromNow());
            }
            if ( data['lease_expires_epoch'] !== null ){
                $('#jamf_lease_expires_epoch').html(moment(parseInt(data['lease_expires_epoch'])).format('llll')+' - '+moment(parseInt(data['lease_expires_epoch'])).fromNow());
            }
            if ( data['last_cloud_backup_date_epoch'] !== null ){
                $('#jamf_last_cloud_backup_date_epoch').html(moment(parseInt(data['last_cloud_backup_date_epoch'])).format('llll')+' - '+moment(parseInt(data['last_cloud_backup_date_epoch'])).fromNow());
            }
            
            // Format battery percent
            if (parseInt(data['battery_capacity']) >= 0) {
                $('#jamf_battery_capacity').text(data['battery_capacity']+"%");
            } else {
                $('#jamf_battery_capacity').text("");
            }

            // Format managed row
            if ( data['managed'] == 1 ){
                $('#jamf_managed').html(i18n.t("yes")+', '+i18n.t("jamf.by")+'<span title="'+i18n.t("jamf.management_password_sha256")+': '+data['management_password_sha256']+'"> '+data['management_username']+'</span>');
            } else {
                $('#jamf_managed').text(i18n.t("no"));
            }
            
            // Format booleans
            if ( data['mdm_capable'] == 1 ){
                $('#jamf_mdm_capable').text(i18n.t("yes"));
            } else {
                $('#jamf_mdm_capable').text(i18n.t("no"));
            }
            if ( data['user_approved_enrollment'] == 1 ){
                $('#jamf_user_approved_enrollment').text(i18n.t("yes"));
            } else {
                $('#jamf_user_approved_enrollment').text(i18n.t("no"));
            }
            if ( data['user_approved_mdm'] == 1 ){
                $('#jamf_user_approved_mdm').text(i18n.t("yes"));
            } else {
                $('#jamf_user_approved_mdm').text(i18n.t("no"));
            }
            if ( data['enrolled_via_dep'] == 1 ){
                $('#jamf_enrolled_via_dep').text(i18n.t("yes"));
            } else {
                $('#jamf_enrolled_via_dep').text(i18n.t("no"));
            }
            if ( data['itunes_store_account_is_active'] == 1 ){
                $('#jamf_itunes_store_account_is_active').text(i18n.t("yes"));
            } else {
                $('#jamf_itunes_store_account_is_active').text(i18n.t("no"));
            }
            if ( data['ble_capable'] == 1 ){
                $('#jamf_ble_capable').text(i18n.t("yes"));
            } else {
                $('#jamf_ble_capable').text(i18n.t("no"));
            }
            if ( data['master_password_set'] == 1 ){
                $('#jamf_master_password_set').text(i18n.t("yes"));
            } else {
                $('#jamf_master_password_set').text(i18n.t("no"));
            }
            if ( data['disable_automatic_login'] == 1 ){
                $('#jamf_disable_automatic_login').text(i18n.t("yes"));
            } else {
                $('#jamf_disable_automatic_login').text(i18n.t("no"));
            }
            if ( data['is_purchased'] == 1 ){
                $('#jamf_is_purchased').text(i18n.t("jamf.is_purchased"));
            } else {
                $('#jamf_is_purchased').text(i18n.t("jamf.is_leased"));
            }
			
            // Format hardware strings
			$('#jamf_processor_speed').html((parseInt(data['processor_speed'])/1000)+" Ghz");
			$('#jamf_cache_size').html('<span title="'+data['cache_size']+' KB">'+Math.round((parseInt(data['cache_size'])/1000), 0)+" MB</span>");
			$('#jamf_total_ram').html('<span title="'+data['total_ram']+' MB">'+Math.round((parseInt(data['total_ram'])/1000), 0)+" GB</span>");
            if ((parseInt(data['bus_speed'])) > 1000 ) { 
                $('#jamf_bus_speed').html((parseInt(data['bus_speed'])/1000)+" Ghz");
            } else if ((parseInt(data['bus_speed'])) > 0 ) { 
                $('#jamf_bus_speed').html(data['bus_speed']+" Mhz");
            } else {
                $('#jamf_bus_speed').html("")
            }
            
            // Format Site Name/ID            
            if (parseInt(data['site_id']) != -1 ){
                $('#jamf_site_name').html('<a title="'+i18n.t("jamf.view_in_jamf")+'" target="_blank" href="'+jamf_server+'/site.html?id='+data['site_id']+'"> '+data['site_name']+'</span>');
                $('#jamf_site_id').text(data['site_id']);
                $('#jamf_site_name_head').removeClass('hide');
                $('#jamf_site_name').removeClass('hide');
                $('#jamf_site_id_head').removeClass('hide');
                $('#jamf_site_id').removeClass('hide');
            } 

            // Make Applications table
            var appsdata = JSON.parse(data['applications']);
            // Set count of installed applications
            $('#jamf-apps-cnt').text(appsdata.length);
            // Make the table framework
            var appsrows = '<h4>'+i18n.t('jamf.applications')+'<a style="float: right;" class="btn btn-default btn-xs" target="_blank" href="'+jamf_server+'/inventoryCollection.html"><i class="fa fa-cog"></i> '+i18n.t("jamf.app_collect_settings")+'</a></h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.version')+'</th><th>'+i18n.t('path')+'</th>'
            $.each(appsdata, function(i,d){
                // Generate rows from data
                appsrows = appsrows + '<tr><td>'+d['name']+'</td><td>'+d['version']+'</td><td>'+d['path']+'</td></tr>';
            })
            $('#Jamf-Applications-Table').html(appsrows+"</tbody></table>") // Close table framework and assign to HTML ID
                    
            
            // Make Printers table
            var printersdata = JSON.parse(data['mapped_printers']);
            // Set count of mapped printers
            $('#jamf-printers-cnt').text(printersdata.length);
            // Make the table framework
            var printersrows = '<h4>'+i18n.t('jamf.printers')+'<a style="float: right;" class="btn btn-default btn-xs" target="_blank" href="'+jamf_server+'/printers.html"><i class="fa fa-cog"></i> '+i18n.t("jamf.printer_settings")+'</a></h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('machine.model')+'</th><th>'+i18n.t('jamf.uri')+'</th><th>'+i18n.t('jamf.location')+'</th>'
            $.each(printersdata, function(i,d){
                // Generate rows from data
                printersrows = printersrows + '<tr><td>'+d['name']+'</td><td>'+d['type']+'</td><td>'+d['uri']+'</td><td>'+d['location']+'</td></tr>';
            })
            $('#Jamf-Printers-Table').html(printersrows+"</tbody></table>") // Close table framework and assign to HTML ID
              
            
            // Make Certificates table
            var certsdata = JSON.parse(data['certificates']);
            // Set count of installed certificates
            $('#jamf-certs-cnt').text(certsdata.length);
            // Make the table framework
            var certssrows = '<h4>'+i18n.t('jamf.certificates')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.common_name')+'</th><th>'+i18n.t('jamf.identity')+'</th><th>'+i18n.t('jamf.expires')+'</th><th>'+i18n.t('username')+'</th>'
            $.each(certsdata, function(i,d){
                // Fix date/time
                var timehuman = '<span title="'+moment(parseInt(d['expires_epoch'])).fromNow()+'">'+moment(parseInt(d['expires_epoch'])).format('llll')+'</span>'
                // Set yes/no
                if (d['identity'] == 1){
                    var identitybool = i18n.t('yes')
                } else {
                    var identitybool = i18n.t('no')
                }
                // Generate rows from data
                certssrows = certssrows + '<tr><td>'+d['common_name']+'</td><td>'+identitybool+'</td><td>'+timehuman+'</td><td>'+d['name']+'</td></tr>';
            })
            $('#Jamf-Certificates-Table').html(certssrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // Make Attachments table
            var attachdata = JSON.parse(data['attachments']);
            // Set count of upload attachments
            $('#jamf-attach-cnt').text(attachdata.length);
            // Make the table framework
            var attachsrows = '<h4>'+i18n.t('jamf.attachments')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.id')+'</th>'
            $.each(attachdata, function(i,d){
                // Generate rows from data
                attachsrows = attachsrows + '<tr><td><a target="_blank" href="'+jamf_server+'/legacy/attachment.html?id='+d['id']+'&o=r">'+d['filename']+'</a></td><td>'+d['id']+'</td></tr>';
            })
            $('#Jamf-Attachments-Table').html(attachsrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // Make Extension Attributes table
            var extensionsdata = JSON.parse(data['extension_attributes']);
            // Set count of extension attributes
            $('#jamf-extensions-cnt').text(extensionsdata.length);
            // Make the table framework
            var extensionsrows = '<h4>'+i18n.t('jamf.extension_attributes')+'<a style="float: right;" class="btn btn-default btn-xs" target="_blank" href="'+jamf_server+'/computerExtensionAttributes.html"><i class="fa fa-cog"></i> '+i18n.t("jamf.extension_attributes")+'</a></h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.attribute')+'</th>'
            if (parseInt(extensionsdata.length) == 0 ){
                    extensionsrows = extensionsrows+'<tr><td>'+i18n.t('jamf.no_extension_attributes')+'</td><td></td></tr>';   
            } else {
                $.each(extensionsdata, function(i,d){
                    // Generate rows from data
                    extensionsrows = extensionsrows + '<tr><td><a target="_blank" href="'+jamf_server+'/computerExtensionAttributes.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td><td>'+d['value']+'</td></tr>';
                })
            }
            $('#Jamf-ExtensionAttributes-Table').html(extensionsrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // Make Services table
            var servicedata = (data['running_services']).split(",");
            // Set count of running services
            $('#jamf-service-cnt').text(servicedata.length);
            // Make the table framework
            var servicesrows = '<h4>'+i18n.t('jamf.services')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            $.each(servicedata, function(i,d){
                // Generate rows from data
                servicesrows = servicesrows + '<tr><td>'+d+'</td></tr>';
            })
            $('#Jamf-Services-Table').html(servicesrows+"</tbody></table>") // Close table framework and assign to HTML ID            
            
            
            // Make LocalUserAccounts tables
            var laccountsdata = JSON.parse(data['local_accounts']);
            // Set count of all local accounts
            $('#jamf-laccounts-cnt').text(laccountsdata.length);
            // Make the table framework
            var laccountrows = ''
            var hlaccountrows = ''
            $.each(laccountsdata, function(i,d){
                // Set yes/no for administrator
                if (d['administrator'] == 1){
                    var adminbool = i18n.t('yes')
                } else {
                    var adminbool = i18n.t('no')
                }
                
                // Set yes/no for filevault_enabled
                if (d['filevault_enabled'] == 1){
                    var fvenabledbool = i18n.t('yes')
                } else {
                    var fvenabledbool = i18n.t('no')
                }
                
                // Generate rows from data
                if ( parseInt(d['uid']) < 500 ) {
                    hlaccountrows = hlaccountrows + '<tr><td>'+d['uid']+'</td><td>'+d['name']+'</td><td>'+d['realname']+'</td><td>'+adminbool+'</td><td>'+d['home']+'</td><td>'+fvenabledbool+'</td></tr>';
                } else {
                    laccountrows = laccountrows + '<tr><td>'+d['uid']+'</td><td>'+d['name']+'</td><td>'+d['realname']+'</td><td>'+adminbool+'</td><td>'+d['home']+'</td><td>'+fvenabledbool+'</td></tr>';
                }
            })
            if (laccountrows != '') {
                var laccounttable = '<h4>'+i18n.t('jamf.localuseraccounts')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.uid')+'</th><th>'+i18n.t('username')+'</th><th>'+i18n.t('jamf.full_name')+'</th><th>'+i18n.t('nav.main.admin')+'</th><th>'+i18n.t('jamf.home_directory')+'</th><th>'+i18n.t('jamf.filevalut_2_enabled')+'</th>'+laccountrows+'</tbody></table>' // Make the local account table
            } else {
                var laccounttable = '<h4>'+i18n.t('jamf.localuseraccounts')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.nolocaluseraccounts')+'</th></tbody></table>' // Make the local account table
            }
            if (hlaccountrows != '') {
                var hlaccounttable = '<h4>'+i18n.t('jamf.hiddenlocaluseraccounts')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.uid')+'</th><th>'+i18n.t('username')+'</th><th>'+i18n.t('jamf.full_name')+'</th><th>'+i18n.t('nav.main.admin')+'</th><th>'+i18n.t('jamf.home_directory')+'</th><th>'+i18n.t('jamf.filevalut_2_enabled')+'</th>'+hlaccountrows+'</tbody></table>' // Make the hidden local account table
            } else {
                var hlaccounttable = '<h4>'+i18n.t('jamf.hiddenlocaluseraccounts')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.nohiddenlocaluseraccounts')+'</th></tbody></table>' // Make the hidden local account table
            }
            $('#Jamf-LocalUserAccounts-Table').html(laccounttable+hlaccounttable) // Combine the tables and assign to HTML ID
                    
           
            // Make PackageReceipts buttons and table
            // Installer.app Packages
            if (data['installed_by_installer_swu'] !== "" && data['installed_by_installer_swu'] !== null) {
                var instpkgsdata = (data['installed_by_installer_swu']).split(",");

                // Set count of installer packages
                var instpkgscount = (instpkgsdata.length);
                $('#jamf-instpkgs-cnt').text(instpkgscount);
                // Make the table framework
                var instpkgsrows = ''
                $.each(instpkgsdata, function(i,d){
                    // Generate rows from data
                    instpkgsrows = instpkgsrows + '<tr><td>'+d+'</td></tr>';
                })
            } else {
                instpkgsrows = '<tr><td>'+i18n.t('jamf.no_package_receipts')+'</td></tr>';
                instpkgscount = 0;
            }
            $('#Jamf-Packages-Installer-Table').html('<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'+instpkgsrows+'</tbody></table>') // Close table framework and assign to HTML ID
            // Jamf Pro Packages
            if (data['installed_by_casper'] !== "" && data['installed_by_casper'] !== null) {
                var jpropkgsdata = (data['installed_by_casper']).split(",");
                
                // Set count of Jamf Pro packages
                var jprocount = (jpropkgsdata.length);
                $('#jamf-jpropkgs-cnt').text(jprocount);
                // Make the table framework
                var jpropkgsrows = ''
                $.each(jpropkgsdata, function(i,d){
                    // Generate rows from data
                    jpropkgsrows = jpropkgsrows + '<tr><td>'+d+'</td></tr>';
                })
            } else {
                jpropkgsrows = '<tr><td>'+i18n.t('jamf.no_package_receipts')+'</td></tr>';
                jprocount = 0;
            }
            $('#Jamf-Packages-JamfPro-Table').html('<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'+jpropkgsrows+'</tbody></table>') // Close table framework and assign to HTML ID
            // Cached Packages
            if (data['cached_by_casper'] !== "" && data['cached_by_casper'] !== null) {
                var cachedpkgsdata = (data['cached_by_casper']).split(",");
                
                // Set count of cached packages
                var cachedcount = (cachedpkgsdata.length);
                $('#jamf-cachedpkgs-cnt').text(cachedcount);
                // Make the table framework
                var cachedpkgsrows = ''
                $.each(cachedpkgsdata, function(i,d){
                    // Generate rows from data
                    cachedpkgsrows = cachedpkgsrows + '<tr><td>'+d+'</td></tr>';
                })
            } else {
                    cachedpkgsrows = '<tr><td>'+i18n.t('jamf.no_package_receipts')+'</td></tr>';
                    cachedcount = 0;
            }
            $('#Jamf-Packages-Cached-Table').html('<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'+cachedpkgsrows+'</tbody></table>') // Close table framework and assign to HTML ID            
            $('#jamf_packages_installer_button').text(i18n.t('jamf.installed_by_installer_swu')+' ('+instpkgscount+')');
            $('#jamf_packages_jamfpro_button').text(i18n.t('jamf.installed_by_casper')+' ('+jprocount+')');
            $('#jamf_packages_cached_button').text(i18n.t('jamf.cached_by_casper')+' ('+cachedcount+')');
            
            
            // Storage and Disk Encryption
            var storagejson = JSON.parse(data['storage']);
            $('#jamf-storage-cnt').text(storagejson.length);
            var storagerows = '<h4>'+i18n.t('jamf.storage')+'</h4>'
            var storageencryptrows = '<h4>'+i18n.t('jamf.diskencryption')+'</h4>'
            $.each(storagejson, function(i,d){
                // Fill in Storage tabs with drive data
                storagerows = storagerows + '<table class="table table-striped table-condensed"><tbody>'
                storagerows = storagerows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.model')+'</th><td class="jamf-table-left">'+d['model']+'</td></tr>';
                storagerows = storagerows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.revision')+'</th><td class="jamf-table-left">'+d['revision']+'</td></tr>';
                storagerows = storagerows + '<tr><th class="jamf-table-right">'+i18n.t('serial')+'</th><td class="jamf-table-left">'+d['serial_number']+'</td></tr>';
                var drive_capacity_bytes = fileSize((d['drive_capacity_mb'] * 1000 * 1000));
                storagerows = storagerows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.drive_capacity_mb')+'</th><td class="jamf-table-left">'+drive_capacity_bytes+'</td></tr>';
                storagerows = storagerows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.smart_status')+'</th><td class="jamf-table-left">'+d['smart_status']+'</td></tr>';
                storagerows = storagerows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.partition_count')+'</th><td class="jamf-table-left">'+d['partition_count']+'</td></tr>';
                
                var partitionrows = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.size_mb')+'</th><th>'+i18n.t('jamf.percent_used')+'</th><th>'+i18n.t('jamf.available')+'</th><th>'+i18n.t('jamf.filevault2_state')+'</th><th>'+i18n.t('jamf.core_storage_partition')+'</th>';
                // Build partitions table
                $.each(d['partitions'], function(i,d){
                    var stintkey = ''
                    var stdiskconfig = ''
                    var stfv2users = ''
                    // Set CoreStorage
                    if (d['lvUUID'] !== undefined){
                        var jcorestorage = i18n.t('yes');
                    } else {
                        var jcorestorage = i18n.t('no');
                    }
                    // Format boot_drive_available_mb
                    if (d['boot_drive_available_mb'] !== undefined){
                        var javailable = fileSize((d['boot_drive_available_mb'] * 1000 * 1000));
                    } else {
                        var javailable = '';
                    }
                    // Format Boot Partition
                    if (d['type'] == 'boot'){
                        stfv2users = '<tr><th class="jamf-table-right">'+i18n.t('jamf.filevault_2_users')+'</th><td class="jamf-table-left">'+data['filevault_2_users']+'</td></tr>';
                        stdiskconfig = '<tr><th class="jamf-table-right">'+i18n.t('jamf.disk_encryption_configuration')+'</th><td class="jamf-table-left">'+data['disk_encryption_configuration']+'</td></tr>';
                        stintkey = '<tr><th class="jamf-table-right">'+i18n.t('jamf.institutional_recovery_key')+'</th><td class="jamf-table-left">'+data['institutional_recovery_key']+'</td></tr>';
                    }
                    // Format FileVault 2
                    if (d['filevault2_status'] !== 'Not Encrypted'){
                        var jamffv2state = d['filevault2_status']+' - '+d['filevault_percent']+'%';
                    } else {
                        var jamffv2state = d['filevault2_status'];
                    }
                    partitionrows = partitionrows + '<tr><td>'+d['name']+'</td><td>'+fileSize((d['size'] * 1000 * 1000))+'</td><td>'+d['percentage_full']+'%</td><td>'+javailable+'</td><td>'+jamffv2state+'</td><td>'+jcorestorage+'</td></tr>';

                    storageencryptrows = storageencryptrows + '<table class="table table-striped table-condensed"><tbody>'
                    storageencryptrows = storageencryptrows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.name')+'</th><td class="jamf-table-left">'+d['name']+'</td></tr>';
                    storageencryptrows = storageencryptrows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.report_date_epoch')+'</th><td class="jamf-table-left">'+moment(parseInt(data['report_date_epoch'])).format('llll')+' - '+moment(parseInt(data['report_date_epoch'])).fromNow()+'</td></tr>';
                    storageencryptrows = storageencryptrows + '<tr><th class="jamf-table-right">'+i18n.t('jamf.filevault2_state')+'</th><td class="jamf-table-left">'+jamffv2state+'</td></tr>';
                    storageencryptrows = storageencryptrows + stintkey
                    storageencryptrows = storageencryptrows + stdiskconfig
                    storageencryptrows = storageencryptrows + stfv2users
                                       
                })
                partitionrows = partitionrows+"</tbody></table></br></br>"; // Close partitions table
                storagerows = storagerows+"</tbody></table></br>"+partitionrows // Close storage table and merge with partitions
                storageencryptrows = storageencryptrows + '</tbody></table></br>'; // Close storage encryption table
            })
            $('#Jamf-Storage-Table').html(storagerows) // Close table framework and assign to HTML ID
            $('#Jamf-Storage-Encryption-Table').html(storageencryptrows) // Close table framework and assign to HTML ID
            
            
            // Make Profiles table
            var profilesdata = JSON.parse(data['configuration_profiles']);
            // Set count of installed profiles
            $('#jamf-profs-cnt').text(profilesdata.length);
            $('#jamf-profs-cnt2').text(profilesdata.length);
            // Make the table framework
            var profilesrows = '<h4>'+i18n.t('jamf.profiles')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.identifier')+'</th><th>'+i18n.t('jamf.level')+'</th><th>'+i18n.t('jamf.id')+'</th><th>'+i18n.t('jamf.description')+'</th><th>'+i18n.t('jamf.distribution_method')+'</th><th>'+i18n.t('jamf.user_removable')+'</th><th>'+i18n.t('jamf.redeploy_on_update')+'</th>'
            var configprofilesrows = '<h4>'+i18n.t('jamf.profiles_scope')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            // Make the table framework
            if (parseInt(profilesdata.length) == 0 ){
                configprofilesrows = configprofilesrows+'<tr><td>'+i18n.t('jamf.no_profiles')+'</td></tr>';   
                profilesrows = profilesrows+'<tr><td>'+i18n.t('jamf.no_profiles')+'</td></tr>';   
            } else {
                $.each(profilesdata, function(i,d){
                    // Set yes/no
                    if (d['user_removable'] == 1){
                        var userremovebool = i18n.t('yes')
                    } else {
                        var userremovebool = i18n.t('no')
                    }
                    // Set level
                    if (d['level'] == 'computer'){
                        var profilelevel = i18n.t('jamf.computer')
                    } else if (d['level'] == 'user') {
                        var profilelevel = i18n.t('user.user')
                    } else {
                        var profilelevel = d['level']
                    }
                    // Generate rows from data
                    profilesrows = profilesrows + '<tr><td><a target="_blank" href="'+jamf_server+'/OSXConfigurationProfiles.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td><td>'+d['uuid']+'</td><td>'+profilelevel+'</td><td>'+d['id']+'</td><td>'+d['description']+'</td><td>'+d['distribution_method']+'</td><td>'+userremovebool+'</td><td>'+d['redeploy_on_update']+'</td></tr>';
                    configprofilesrows = configprofilesrows + '<tr><td><a target="_blank" href="'+jamf_server+'/OSXConfigurationProfiles.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td></tr>';

                })
            }
            $('#Jamf-Profiles-Table').html(profilesrows+"</tbody></table>") // Close table framework and assign to HTML ID
            $('#Jamf-ConfigurationProfiles-Table').html(configprofilesrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // Management Commands
            var commandsjson = JSON.parse(data['commands_history']);
            var commandscompleted = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.command')+'</th><th>'+i18n.t('jamf.date_completed')+'</th><th>'+i18n.t('jamf.username')+'</th>'
            var commandsfailed = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.command')+'</th><th>'+i18n.t('jamf.status')+'</th><th>'+i18n.t('jamf.date_issued')+'</th><th>'+i18n.t('jamf.date_last_push')+'</th><th>'+i18n.t('jamf.username')+'</th>'
            var commandspending = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.command')+'</th><th>'+i18n.t('jamf.status')+'</th><th>'+i18n.t('jamf.date_issued')+'</th><th>'+i18n.t('jamf.date_last_push')+'</th><th>'+i18n.t('jamf.username')+'</th>'
            if (parseInt(commandsjson['completed'].length) == 0 ){
                commandscompleted = commandscompleted+'<tr><td>'+i18n.t('jamf.no_completed_commands')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(commandsjson['completed'], function(i,d){ // Process completed commands
                // Fix date/time
                var timehuman = '<span title="'+moment(parseInt(d['completed_epoch'])).fromNow()+'">'+moment(parseInt(d['completed_epoch'])).format('llll')+'</span>'
                // Generate rows from data
                commandscompleted = commandscompleted+'<tr><td>'+d['name']+'</td><td>'+timehuman+'</td><td>'+d['username']+'</td></tr>';
                })
            }
            if (parseInt(commandsjson['failed'].length) == 0 ){
                commandsfailed = commandsfailed+'<tr><td>'+i18n.t('jamf.no_failed_commands')+'</td><td></td><td></td><td></td><td></td></tr>';   
            } else {
                $.each(commandsjson['failed'], function(i,d){ // Process failed commands
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['issued_epoch'])).fromNow()+'">'+moment(parseInt(d['issued_epoch'])).format('llll')+'</span>'
                    var timehuman2 = '<span title="'+moment(parseInt(d['last_push_epoch'])).fromNow()+'">'+moment(parseInt(d['last_push_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    commandsfailed = commandsfailed+'<tr><td>'+d['name']+'</td><td>'+d['status']+'</td><td>'+timehuman+'</td><td>'+timehuman2+'</td><td>'+d['username']+'</td></tr>';
                })
            }
            if (parseInt(commandsjson['pending'].length) == 0 ){
                    commandspending = commandspending+'<tr><td>'+i18n.t('jamf.no_pending_commands')+'</td><td></td><td></td><td></td><td></td></tr>';   
            } else {
                $.each(commandsjson['pending'], function(i,d){ // Process pending commands
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['issued_epoch'])).fromNow()+'">'+moment(parseInt(d['issued_epoch'])).format('llll')+'</span>'
                    var timehuman2 = '<span title="'+moment(parseInt(d['last_push_epoch'])).fromNow()+'">'+moment(parseInt(d['last_push_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    commandspending = commandspending+'<tr><td>'+d['name']+'</td><td>'+d['status']+'</td><td>'+timehuman+'</td><td>'+timehuman2+'</td><td>'+d['username']+'</td></tr>';
                })
            }
            $('#jamf_managementhistory_completed_button').text(data['comands_completed']+' '+i18n.t('jamf.completed_commands')); // Close table framework and assign to HTML ID 
            $('#jamf_managementhistory_failed_button').text(data['comands_failed']+' '+i18n.t('jamf.failed_commands')); // Close table framework and assign to HTML ID 
            $('#jamf_managementhistory_pending_button').text(data['comands_pending']+' '+i18n.t('jamf.pending_commands')); // Close table framework and assign to HTML ID 
            $('#Jamf-ManagementHistory-Completed-Table').html(commandscompleted) // Close table framework and assign to HTML ID            
            $('#Jamf-ManagementHistory-Failed-Table').html(commandsfailed) // Close table framework and assign to HTML ID            
            $('#Jamf-ManagementHistory-Pending-Table').html(commandspending) // Close table framework and assign to HTML ID            
            $('#Jamf-ManagementCommands-Table').html('<h4>'+i18n.t('jamf.pending_commands')+'&nbsp;&nbsp;<span class="badge">'+data['comands_pending']+'</h4>'+commandspending+"</tbody></table><h4>"+i18n.t('jamf.failed_commands')+'&nbsp;&nbsp;<span class="badge">'+data['comands_failed']+'</span>'+'</h4>'+commandsfailed+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // Make Policies table
            var policiesdata = JSON.parse(data['policies_management']);
            // Set count of policies
            $('#jamf-policies-cnt').text(policiesdata.length);
            // Make the table framework
            var policiesrows = '<h4>'+i18n.t('jamf.policies_scope')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.triggers')+'</th>'
            if (parseInt(policiesdata.length) == 0 ){
                    policiesrows = policiesrows+'<tr><td>'+i18n.t('jamf.no_policies')+'</td></tr>';   
            } else {
                $.each(policiesdata, function(i,d){
                    // Generate rows from data
                    policiesrows = policiesrows + '<tr><td><a target="_blank" href="'+jamf_server+'/policies.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td><td>'+d['triggers']+'</td></tr>';
                })
            }
            $('#Jamf-Policies-Table').html(policiesrows+"</tbody></table>") // Close table framework and assign to HTML ID
                    
                    
            // Make eBooks table
            var ebooksdata = JSON.parse(data['ebooks_management']);
            // Set count of eBooks
            $('#jamf-ebooks-cnt').text(ebooksdata.length);
            // Make the table framework
            var ebooksrows = '<h4>'+i18n.t('jamf.ebooks_scope')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            if (parseInt(ebooksdata.length) == 0 ){
                    ebooksrows = ebooksrows+'<tr><td>'+i18n.t('jamf.no_ebooks')+'</td></tr>';   
            } else {
                $.each(ebooksdata, function(i,d){
                    // Generate rows from data
                    ebooksrows = ebooksrows + '<tr><td><a target="_blank" href="'+jamf_server+'/eBooks.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td></tr>';
                })
            }
            $('#Jamf-eBooks-Table').html(ebooksrows+"</tbody></table>") // Close table framework and assign to HTML ID
                        
                
            // Make Mac App Store table
            var macappssdata = JSON.parse(data['mac_app_store_applications_history']);
            // Set count of Mac App Store Apps
            $('#jamf-macapps-cnt').text(macappssdata['installed'].length);
            // Make the table framework
            var macappsrows = '<h4>'+i18n.t('jamf.mac_apps_scope')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            if (parseInt(macappssdata.length) == 0 ){
                    macappsrows = macappsrows+'<tr><td>'+i18n.t('jamf.no_mac_apps')+'</td></tr>';   
            } else {
                $.each(macappssdata['installed'], function(i,d){
                    // Generate rows from data
                    if (d["id"] !== undefined) {
                        macappsrows = macappsrows + '<tr><td><a target="_blank" href="'+jamf_server+'/macApps.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td></tr>';
                    } else {
                        macappsrows = macappsrows + '<tr><td>'+d['name']+'</td></tr>';
                    }
                })
            }
            $('#Jamf-MacApps-Table').html(macappsrows+"</tbody></table>") // Close table framework and assign to HTML ID
                        
                
            // Make Managed Preferences table
            var manprefsdata = JSON.parse(data['managed_preference_profiles_management']);
            // Set count of Managed Preferences
            $('#jamf-manprefs-cnt').text(manprefsdata.length);
            // Make the table framework
            var manprefsrows = '<h4>'+i18n.t('jamf.preferences_scope')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            if (parseInt(manprefsdata.length) == 0 ){
                    manprefsrows = manprefsrows+'<tr><td>'+i18n.t('jamf.no_preferences')+'</td></tr>';   
            } else {
                $.each(manprefsdata, function(i,d){
                    // Generate rows from data
                    manprefsrows = manprefsrows + '<tr><td>'+d['name']+'</td></tr>';
                })
            }
            $('#Jamf-ManagedPreferences-Table').html(manprefsrows+"</tbody></table>") // Close table framework and assign to HTML ID   
                
                
            // Make Restricted Software table
            var restrictedsoftdata = JSON.parse(data['restricted_software_management']);
            // Set count of Restricted Software
            $('#jamf-restsoft-cnt').text(restrictedsoftdata.length);
            // Make the table framework
            var restrictedsoftrows = '<h4>'+i18n.t('jamf.restricted_scope')+'</h4><table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            if (parseInt(restrictedsoftdata.length) == 0 ){
                    restrictedsoftrows = restrictedsoftrows+'<tr><td>'+i18n.t('jamf.no_restricted')+'</td></tr>';   
            } else {
                $.each(restrictedsoftdata, function(i,d){
                    // Generate rows from data
                    restrictedsoftrows = restrictedsoftrows + '<tr><td><a target="_blank" href="'+jamf_server+'/restrictedSoftware.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td></tr>';
                })
            }
            $('#Jamf-RestrictedSoftware-Table').html(restrictedsoftrows+"</tbody></table>") // Close table framework and assign to HTML ID
                    
    
            // Smart Groups Table
            var groupssmartdata = JSON.parse(data['smart_groups_management']);
            // Set count of Smart Groups
            $('#jamf_groups_smart_button').text(i18n.t('jamf.smart_groups_management')+' ('+groupssmartdata.length+')');
            // Make the table framework
            var groupssmartrows = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            if (parseInt(groupssmartdata.length) == 0 ){
                    groupssmartrows = groupssmartrows+'<tr><td>'+i18n.t('jamf.no_smart_groups')+'</td></tr>';   
            } else {
                $.each(groupssmartdata, function(i,d){
                    // Generate rows from data
                    groupssmartrows = groupssmartrows + '<tr><td><a target="_blank" href="'+jamf_server+'/smartComputerGroups.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td></tr>';
                })
            }
            $('#Jamf-Groups-Smart-Table').html(groupssmartrows+"</tbody></table>") // Close table framework and assign to HTML ID            
                
                
            // Static Groups Table
            var groupsstaticdata = JSON.parse(data['static_groups_management']);
            // Set count of Static Groups
            $('#jamf_groups_static_button').text(i18n.t('jamf.static_groups_management')+' ('+groupsstaticdata.length+')');
            // Make the table framework
            var groupsstaticrows = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th>'
            if (parseInt(groupsstaticdata.length) == 0 ){
                    groupsstaticrows = groupsstaticrows+'<tr><td>'+i18n.t('jamf.no_static_groups')+'</td></tr>';   
            } else {
                $.each(groupsstaticdata, function(i,d){
                    // Generate rows from data
                    groupsstaticrows = groupsstaticrows + '<tr><td><a target="_blank" href="'+jamf_server+'/staticComputerGroups.html?id='+d["id"]+'&o=r'+'">'+d['name']+'</a></td></tr>';
                })
            }
            $('#Jamf-Groups-Static-Table').html(groupsstaticrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // Software Titles Table
            var softwaretitlesdata = JSON.parse(data['patch_reporting_software_titles_management']);
            // Set count of Software Titles
            if (softwaretitlesdata.length !== undefined) {
                var softwaretitlescount = softwaretitlesdata.length;
            } else {
                var softwaretitlescount = 0
            }
            $('#jamf_software_titles_button').text(i18n.t('jamf.software_titles')+' ('+softwaretitlescount+')');
            // Make the table framework
            var softwaretitlesrows = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.latest_version')+'</th><th>'+i18n.t('jamf.installed_version')+'</th>'
            if (softwaretitlescount == 0 ){
                    softwaretitlesrows = softwaretitlesrows+'<tr><td>'+i18n.t('jamf.no_software_title')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(softwaretitlesdata, function(i,d){
                    // Generate rows from data
                    softwaretitlesrows = softwaretitlesrows + '<tr><td>'+d['name']+'</td><td>'+d['latest_version']+'</td><td>'+d['installed_version']+'</td></tr>';
                })
            }
            $('#Jamf-Software-Titles-Table').html(softwaretitlesrows+"</tbody></table>") // Close table framework and assign to HTML ID            
            
            
            // Patch Policies Table
            var patchpoliciesdata = JSON.parse(data['patch_policies_management']);
            // Set count of Patch Policies
            if (patchpoliciesdata.length !== undefined) {
                var patchpoliciescount = patchpoliciesdata.length;
            } else {
                var patchpoliciescount = 0
            }
            $('#jamf_patch_policies_button').text(i18n.t('jamf.patch_policies')+' ('+patchpoliciescount+')');
            // Make the table framework
            var patchpoliciesrows = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.id')+'</th>'
            if (patchpoliciescount == 0 ){
                    patchpoliciesrows = patchpoliciesrows+'<tr><td>'+i18n.t('jamf.no_patch_policies')+'</td><td></td>';   
            } else {
                $.each(patchpoliciesdata, function(i,d){
                    // Generate rows from data
                    patchpoliciesrows = patchpoliciesrows + '<tr><td>'+d['name']+'</td><td>'+d['id']+'</td></tr>';
                })
            }
            $('#Jamf-Patch-Policies-Table').html(patchpoliciesrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // ComputerUsage Table
            var computerusagedata = JSON.parse(data['computer_usage_logs_history']);
            // Make the table framework
            var computerusagerows = '<h4>'+i18n.t('jamf.computer_usage_logs')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.event')+'</th><th>'+i18n.t('jamf.username_policy')+'</th><th>'+i18n.t('jamf.date_policy')+'</th></tr></thead>'
            if (parseInt(computerusagedata.length) == 0 ){
                    computerusagerows = computerusagerows+'<tr><td>'+i18n.t('jamf.no_computer_usage_logs')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(computerusagedata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_time_epoch'])).fromNow()+'">'+moment(parseInt(d['date_time_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    computerusagerows = computerusagerows + '<tr><td>'+d['event']+'</td><td>'+d["username"]+'</td><td>'+timehuman+'</td></tr>';
                })
            }
            $('#Jamf-ComputerUsage-Table').html(computerusagerows+"</tbody></table>") // Close table framework and assign to HTML ID
            
              
            // AuditLogs Table
            var auditdata = JSON.parse(data['audits_history']);
            // Make the table framework
            var auditrows = '<h4>'+i18n.t('jamf.audits_history')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.event')+'</th><th>'+i18n.t('jamf.username')+'</th><th>'+i18n.t('jamf.date_policy')+'</th></tr></thead>'
            if (parseInt(auditdata.length) == 0 ){
                    auditrows = auditrows+'<tr><td>'+i18n.t('jamf.no_audits_history')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(auditdata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_time_epoch'])).fromNow()+'">'+moment(parseInt(d['date_time_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    auditrows = auditrows + '<tr><td>'+d['event']+'</td><td>'+d["username"]+'</td><td>'+timehuman+'</td></tr>';
                })
            }
            $('#Jamf-AuditLogs-Table').html(auditrows+"</tbody></table>") // Close table framework and assign to HTML ID
            
            
            // PolicyLogs Table
            var policylogsdata = JSON.parse(data['policy_logs_history']);
            // Make the table framework
            var policylogsrows = '<h4>'+i18n.t('jamf.policy_logs')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.policy')+'</th><th>'+i18n.t('jamf.username_policy')+'</th><th>'+i18n.t('jamf.date_policy')+'</th><th>'+i18n.t('jamf.status')+'</th></tr></thead>'
            if (parseInt(policylogsdata.length) == 0 ){
                    policylogsrows = policylogsrows+'<tr><td>'+i18n.t('jamf.no_policy_logs')+'</td><td></td><td></td><td></td></tr>';   
            } else {
                $.each(policylogsdata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_completed_epoch'])).fromNow()+'">'+moment(parseInt(d['date_completed_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    policylogsrows = policylogsrows + '<tr><td><a target="_blank" href="'+jamf_server+'/policies.html?id='+d["policy_id"]+'&o=r'+'">'+d['policy_name']+'</a></td><td>'+d["username"]+'</td><td>'+timehuman+'</td><td>'+d["status"]+'</td></tr>';
                })
            }
            $('#Jamf-PolicyLogs-Table').html(policylogsrows+"</tbody></table>") // Close table framework and assign to HTML ID 
            

            // JamfRemote Table
            var remotedata = JSON.parse(data['casper_remote_logs_history']);
            // Make the table framework
            var remoterows = '<h4>'+i18n.t('jamf.casper_remote_logs_history')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.date_policy')+'</th><th>'+i18n.t('jamf.status')+'</th></tr></thead>'
            if (parseInt(remotedata.length) == 0 ){
                    remoterows = remoterows+'<tr><td>'+i18n.t('jamf.no_casper_remote_logs_history')+'</td><td></td></tr>';   
            } else {
                $.each(remotedata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_time_epoch'])).fromNow()+'">'+moment(parseInt(d['date_time_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    remoterows = remoterows + '<tr><td>'+timehuman+'</td><td>'+d['status']+'</td></tr>';
                })
            }
            $('#Jamf-JamfRemote-Table').html(remoterows+"</tbody></table>") // Close table framework and assign to HTML ID

            
            // ScreenSharingLogs Table
            var screensharingdata = JSON.parse(data['screen_sharing_logs_history']);
            // Make the table framework
            var screensharingrows = '<h4>'+i18n.t('jamf.screen_sharing_logs_history')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.date_policy')+'</th><th>'+i18n.t('jamf.status')+'</th><th>'+i18n.t('jamf.details')+'</th></tr></thead>'
            if (parseInt(screensharingdata.length) == 0 ){
                    screensharingrows = screensharingrows+'<tr><td>'+i18n.t('jamf.no_screen_sharing_logs_history')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(screensharingdata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_time_epoch'])).fromNow()+'">'+moment(parseInt(d['date_time_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    screensharingrows = screensharingrows + '<tr><td>'+timehuman+'</td><td>'+d['status']+'</td><td>'+d["details"]+'</td></tr>';
                })
            }
            $('#Jamf-ScreenSharingLogs-Table').html(screensharingrows+"</tbody></table>") // Close table framework and assign to HTML ID


            // JamfImaging Table
            var imagingdata = JSON.parse(data['casper_imaging_logs_history']);
            // Make the table framework
            var imagingrows = '<h4>'+i18n.t('jamf.casper_imaging_logs')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.date_policy')+'</th><th>'+i18n.t('jamf.status')+'</th></tr></thead>'
            if (parseInt(imagingdata.length) == 0 ){
                    imagingrows = imagingrows+'<tr><td>'+i18n.t('jamf.no_casper_imaging_logs')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(imagingdata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_time_epoch'])).fromNow()+'">'+moment(parseInt(d['date_time_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    imagingrows = imagingrows + '<tr><td>'+timehuman+'</td><td>'+d['status']+'</td></tr>';
                })
            }
            $('#Jamf-JamfImaging-Table').html(imagingrows+"</tbody></table>") // Close table framework and assign to HTML ID        
            
                
            // UserLocation History Table
            var locationuserdata = JSON.parse(data['user_location_history']);
            // Make the table framework
            var locationuserrows = '<h4>'+i18n.t('jamf.userlocation')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('jamf.date_policy')+'</th><th>'+i18n.t('jamf.username')+'</th><th>'+i18n.t('jamf.realname')+'</th><th>'+i18n.t('jamf.email_address')+'</th><th>'+i18n.t('jamf.phone')+'</th><th>'+i18n.t('jamf.department')+'</th><th>'+i18n.t('jamf.building')+'</th><th>'+i18n.t('jamf.room')+'</th><th>'+i18n.t('jamf.position')+'</th></tr></thead>'
            if (parseInt(locationuserdata.length) == 0 ){
                    locationuserrows = locationuserrows+'<tr><td>'+i18n.t('jamf.no_userlocation')+'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';   
            } else {
                $.each(locationuserdata, function(i,d){
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['date_time_epoch'])).fromNow()+'">'+moment(parseInt(d['date_time_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    locationuserrows = locationuserrows + '<tr><td>'+timehuman+'</td><td>'+d["username"]+'</td><td>'+d['full_name']+'</td><td>'+d['email_address']+'</td><td>'+d['phone_number']+'</td><td>'+d['department']+'</td><td>'+d['building']+'</td><td>'+d['room']+'</td><td>'+d['position']+'</td></tr>';
                })
            }
            $('#Jamf-UserLocationHistory-Table').html(locationuserrows+"</tbody></table>") // Close table framework and assign to HTML ID            
            
            
            // Mac App Store Apps History Table
            var macappshistoryjson = JSON.parse(data['mac_app_store_applications_history']);
            var macappshistoryinstalled = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.version')+'</th><th>'+i18n.t('jamf.size_mb')+'</th>'
            var macappshistoryfailed = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.version')+'</th><th>'+i18n.t('error')+'</th><th>'+i18n.t('jamf.date_deployed')+'</th><th>'+i18n.t('jamf.date_last_update')+'</th>'
            var macappshistorypending = '<table class="table table-striped table-condensed"><tbody><th>'+i18n.t('jamf.name')+'</th><th>'+i18n.t('jamf.version')+'</th><th>'+i18n.t('jamf.status')+'</th><th>'+i18n.t('jamf.date_deployed')+'</th><th>'+i18n.t('jamf.date_last_update')+'</th>'
            if (parseInt(macappshistoryjson['installed'].length) == 0 ){
                macappshistoryinstalled = macappshistoryinstalled+'<tr><td>'+i18n.t('jamf.no_installed_apps')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(macappshistoryjson['installed'], function(i,d){ // Process installed Mac App Store apps
                // Format app size
                var appsize_mb = fileSize((d['size_mb'] * 1000 * 1000));
                // Generate rows from data
                macappshistoryinstalled = macappshistoryinstalled+'<tr><td>'+d['name']+'</td><td>'+d['version']+'</td><td>'+appsize_mb+'</td></tr>';
                })
            }
            if (parseInt(macappshistoryjson['failed'].length) == 0 ){
                macappshistoryfailed = macappshistoryfailed+'<tr><td>'+i18n.t('jamf.no_failed_apps')+'</td><td></td><td></td><td></td><td></td></tr>';   
            } else {
                $.each(macappshistoryjson['failed'], function(i,d){ // Process failed Mac App Store apps
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['issued_epoch'])).fromNow()+'">'+moment(parseInt(d['deployed_epoch'])).format('llll')+'</span>'
                    var timehuman2 = '<span title="'+moment(parseInt(d['last_push_epoch'])).fromNow()+'">'+moment(parseInt(d['last_update_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    macappshistoryfailed = macappshistoryfailed+'<tr><td>'+d['name']+'</td><td>'+d['version']+'</td><td>'+d['staus']+'</td><td>'+timehuman+'</td><td>'+timehuman2+'</td></tr>';
                })
            }
            if (parseInt(macappshistoryjson['pending'].length) == 0 ){
                    macappshistorypending = macappshistorypending+'<tr><td>'+i18n.t('jamf.no_pending_apps')+'</td><td></td><td></td><td></td><td></td></tr>';   
            } else {
                $.each(macappshistoryjson['pending'], function(i,d){ // Process pending Mac App Store apps
                    // Fix date/time
                    var timehuman = '<span title="'+moment(parseInt(d['issued_epoch'])).fromNow()+'">'+moment(parseInt(d['issued_epoch'])).format('llll')+'</span>'
                    var timehuman2 = '<span title="'+moment(parseInt(d['last_push_epoch'])).fromNow()+'">'+moment(parseInt(d['last_push_epoch'])).format('llll')+'</span>'
                    // Generate rows from data
                    macappshistorypending = macappshistorypending+'<tr><td>'+d['name']+'</td><td>'+d['version']+'</td><td>'+d['staus']+'</td><td>'+timehuman+'</td><td>'+timehuman2+'</td></tr>';
                })
            }
            $('#jamf_macapps_installed_button').text(macappshistoryjson['installed'].length +' '+i18n.t('jamf.installed_apps')); // Close table framework and assign to HTML ID 
            $('#jamf_macapps_failed_button').text(macappshistoryjson['failed'].length +' '+i18n.t('jamf.pending_apps')); // Close table framework and assign to HTML ID 
            $('#jamf_macapps_pending_button').text(macappshistoryjson['pending'].length +' '+i18n.t('jamf.failed_apps')); // Close table framework and assign to HTML ID 
            $('#Jamf-MacApps-Installed-Table').html(macappshistoryinstalled) // Close table framework and assign to HTML ID            
            $('#Jamf-MacApps-Failed-Table').html(macappshistoryfailed) // Close table framework and assign to HTML ID            
            $('#Jamf-MacApps-Pending-Table').html(macappshistorypending) // Close table framework and assign to HTML ID            
            
            
			// Add strings
			$('#jamf_serial_number').text(data['serial_number']);
			$('#jamf_id').text(data['jamf_id']); 
			$('#jamf_name').text(data['name']);
			$('#jamf_mac_address').text(data['mac_address']);
			$('#jamf_alt_mac_address').text(data['alt_mac_address']);
			$('#jamf_ip_address').text(data['ip_address']);
			$('#jamf_last_reported_ip').text(data['last_reported_ip']);
			$('#jamf_version').text(data['jamf_version']);
			$('#jamf_barcode_1').text(data['barcode_1']);
			$('#jamf_barcode_2').text(data['barcode_2']);
			$('#jamf_asset_tag').text(data['asset_tag']);
			$('#jamf_mdm_capable_users').text(data['mdm_capable_users']);
			$('#jamf_distribution_point').text(data['distribution_point']);
			$('#jamf_sus').text(data['sus']);
			$('#jamf_netboot_server').text(data['netboot_server']);
			$('#jamf_udid').text(data['udid']);
			$('#jamf_username').text(data['username']);
			$('#jamf_realname').text(data['realname']);
			$('#jamf_email_address').text(data['email_address']);
			$('#jamf_position').text(data['position']);
			$('#jamf_phone').text(data['phone']);
			$('#jamf_department').text(data['department']);
			$('#jamf_building').text(data['building']);
			$('#jamf_room').text(data['room']);
			$('#jamf_po_number').text(data['po_number']);
			$('#jamf_vendor').text(data['vendor']);
			$('#jamf_applecare_id').text(data['applecare_id']);
			$('#jamf_purchase_price').text(data['purchase_price']);
			$('#jamf_purchasing_account').text(data['purchasing_account']);
			$('#jamf_purchasing_contact').text(data['purchasing_contact']);
			$('#jamf_life_expectancy').text(data['life_expectancy']);
			$('#jamf_active_directory_status').text(data['active_directory_status']);
			$('#jamf_available_ram_slots').text(data['available_ram_slots']);
			$('#jamf_boot_rom').text(data['boot_rom']);
			$('#jamf_disk_encryption_configuration').text(data['disk_encryption_configuration']);
			$('#jamf_filevault_2_users').text(data['filevault_2_users']);
			$('#jamf_gatekeeper_status').text(data['gatekeeper_status']);
			$('#jamf_institutional_recovery_key').text(data['institutional_recovery_key']);
			$('#jamf_model').text(data['model']);
			$('#jamf_model_identifier').text(data['model_identifier']);
			$('#jamf_nic_speed').text(data['nic_speed']);
			$('#jamf_number_cores').text(data['number_cores']);
			$('#jamf_number_processors').text(data['number_processors']);
			$('#jamf_optical_drive').text(data['optical_drive']);
			$('#jamf_os_build').text(data['os_build']);
			$('#jamf_os_version').text(data['os_version']);
			$('#jamf_processor_architecture').text(data['processor_architecture']);
			$('#jamf_processor_type').text(data['processor_type']);
			$('#jamf_sip_status').text(data['sip_status']);
			$('#jamf_smc_version').text(data['smc_version']);
			$('#jamf_xprotect_version').text(data['xprotect_version']);
			$('#jamf_licensed_software').text(data['licensed_software']);
			$('#jamf_available_software_updates').text(data['available_software_updates']);
			$('#jamf_computer_group_memberships').text(data['computer_group_memberships']);
		}

	});

});
    
// Make button groups active
$(".btn-group > .btn").click(function(){
    $(this).addClass("active").siblings().removeClass("active");
});

</script>
