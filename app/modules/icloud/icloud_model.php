<?php

use CFPropertyList\CFPropertyList;

class Icloud_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'icloud'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['account_alternate_dsid'] = '';
        $this->rs['account_description'] = '';
        $this->rs['account_dsid'] = 0;
        $this->rs['account_id'] = '';
        $this->rs['account_uuid'] = '';
        $this->rs['back_to_my_mac_enabled'] = 0;
        $this->rs['back_to_my_mac_relay_port'] = 0;
        $this->rs['beta'] = 0;
        $this->rs['bookmarks_enabled'] = 0;
        $this->rs['calendar_enabled'] = 0;
        $this->rs['cloud_photo_enabled'] = 0;
        $this->rs['cloud_photo_only_keep_thumbnail'] = 0;
        $this->rs['clouddesktop_declined_upgrade'] = 0;
        $this->rs['clouddesktop_desktop_enabled'] = 0;
        $this->rs['clouddesktop_documents_enabled'] = 0;
        $this->rs['clouddesktop_drive_enabled'] = 0;
        $this->rs['clouddesktop_first_sync_down_complete'] = 0;
        $this->rs['contacts_enabled'] = 0;
        $this->rs['display_name'] = '';
        $this->rs['family_show_manage_family'] = 0;
        $this->rs['find_my_mac_enabled'] = 0;
        $this->rs['is_managed_apple_id'] = 0;
        $this->rs['keychain_sync_enabled'] = 0;
        $this->rs['logged_in'] = 0;
        $this->rs['mail_and_notes_dot_mac_mail_supported'] = 0;
        $this->rs['mail_and_notes_email_address'] = '';
        $this->rs['mail_and_notes_enabled'] = 0;
        $this->rs['mail_and_notes_full_user_name'] = '';
        $this->rs['mail_and_notes_username'] = '';
        $this->rs['mobile_documents_enabled'] = 0;
        $this->rs['notes_enabled'] = 0;
        $this->rs['photo_stream_enabled'] = 0;
        $this->rs['prefpath'] = '';
        $this->rs['primary_email_verified'] = 0;
        $this->rs['reminders_enabled'] = 0;
        $this->rs['shared_streams_enabled'] = 0;
        $this->rs['should_configure'] = 0;
        $this->rs['siri_enabled'] = 0;
        $this->rs['imessage_syncing_enabled'] = 0;
        $this->rs['imessage_currently_syncing'] = 0;

        $this->serial = $serial;
    }


    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing icloud module: No data found");
        } else { 
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming icloud.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Process each account
            foreach ($plist as $account) {
                // Process each key in the account, if it exists
                foreach (array('account_alternate_dsid', 'account_description', 'account_dsid', 'account_id', 'account_uuid', 'back_to_my_mac_enabled', 'back_to_my_mac_relay_port', 'beta', 'bookmarks_enabled', 'calendar_enabled', 'cloud_photo_enabled', 'cloud_photo_only_keep_thumbnail', 'clouddesktop_declined_upgrade', 'clouddesktop_desktop_enabled', 'clouddesktop_documents_enabled', 'clouddesktop_drive_enabled', 'clouddesktop_first_sync_down_complete', 'contacts_enabled', 'display_name', 'family_show_manage_family', 'find_my_mac_enabled', 'is_managed_apple_id', 'keychain_sync_enabled', 'logged_in', 'mail_and_notes_dot_mac_mail_supported', 'mail_and_notes_email_address', 'mail_and_notes_enabled', 'mail_and_notes_full_user_name', 'mail_and_notes_username', 'mobile_documents_enabled', 'notes_enabled', 'photo_stream_enabled', 'prefpath', 'primary_email_verified', 'reminders_enabled', 'shared_streams_enabled', 'should_configure', 'siri_enabled', 'imessage_syncing_enabled', 'imessage_currently_syncing') as $item) {

                    // If key does not exist in $account, null it
                    if ( ! array_key_exists($item, $account)) {
                        $this->$item = null;
                    // Set the db fields to be the same as those in the preference file
                    } else {
                        $this->$item = $account[$item];
                    }
                }
            // Save the data
            $this->id = '';
            $this->save(); 
            }
        }
    }
}