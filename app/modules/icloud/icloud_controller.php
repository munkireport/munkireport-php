<?php
/**
 * icloud module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Icloud_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
    
    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the icloud module!";
    }
    
    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT display_name, account_id, logged_in, account_description, prefpath, account_dsid, account_alternate_dsid, account_uuid, beta, family_show_manage_family, is_managed_apple_id, primary_email_verified, should_configure,
        clouddesktop_drive_enabled, clouddesktop_desktop_enabled, clouddesktop_documents_enabled, clouddesktop_first_sync_down_complete, clouddesktop_declined_upgrade, mobile_documents_enabled, cloud_photo_enabled, photo_stream_enabled, shared_streams_enabled, cloud_photo_only_keep_thumbnail, mail_and_notes_enabled, mail_and_notes_email_address, mail_and_notes_full_user_name, mail_and_notes_username, mail_and_notes_dot_mac_mail_supported, contacts_enabled, calendar_enabled, reminders_enabled, bookmarks_enabled, notes_enabled, keychain_sync_enabled, siri_enabled, imessage_syncing_enabled, imessage_currently_syncing, back_to_my_mac_enabled, back_to_my_mac_relay_port, find_my_mac_enabled
                        FROM icloud 
                        WHERE serial_number = '$serial_number'";
        
        $queryobj = new Icloud_model();
        $icloud_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $icloud_tab)))); 
    }
} // END class Icloud_controller