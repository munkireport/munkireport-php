<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Icloud extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('icloud', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('account_alternate_dsid')->nullable();
            $table->string('account_description')->nullable();
            $table->bigInteger('account_dsid')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_uuid')->nullable();
            $table->boolean('back_to_my_mac_enabled')->nullable();
            $table->integer('back_to_my_mac_relay_port')->nullable();
            $table->boolean('beta')->nullable();
            $table->boolean('bookmarks_enabled')->nullable();
            $table->boolean('calendar_enabled')->nullable();
            $table->boolean('cloud_photo_enabled')->nullable();
            $table->boolean('cloud_photo_only_keep_thumbnail')->nullable();
            $table->boolean('clouddesktop_declined_upgrade')->nullable();
            $table->boolean('clouddesktop_desktop_enabled')->nullable();
            $table->boolean('clouddesktop_documents_enabled')->nullable();
            $table->boolean('clouddesktop_drive_enabled')->nullable();
            $table->boolean('clouddesktop_first_sync_down_complete')->nullable();
            $table->boolean('contacts_enabled')->nullable();
            $table->string('display_name')->nullable();
            $table->boolean('family_show_manage_family')->nullable();
            $table->boolean('find_my_mac_enabled')->nullable();
            $table->boolean('is_managed_apple_id')->nullable();
            $table->boolean('keychain_sync_enabled')->nullable();
            $table->boolean('logged_in')->nullable();
            $table->boolean('mail_and_notes_dot_mac_mail_supported')->nullable();
            $table->string('mail_and_notes_email_address')->nullable();
            $table->boolean('mail_and_notes_enabled')->nullable();
            $table->string('mail_and_notes_full_user_name')->nullable();
            $table->string('mail_and_notes_username')->nullable();
            $table->boolean('mobile_documents_enabled')->nullable();
            $table->boolean('notes_enabled')->nullable();
            $table->boolean('photo_stream_enabled')->nullable();
            $table->string('prefpath')->nullable();
            $table->boolean('primary_email_verified')->nullable();
            $table->boolean('reminders_enabled')->nullable();
            $table->boolean('shared_streams_enabled')->nullable();
            $table->boolean('should_configure')->nullable();
            $table->boolean('siri_enabled')->nullable();
            $table->boolean('imessage_syncing_enabled')->nullable();
            $table->boolean('imessage_currently_syncing')->nullable();

            $table->index('account_alternate_dsid');
            $table->index('account_description');
            $table->index('account_dsid');
            $table->index('account_id');
            $table->index('account_uuid');
            $table->index('back_to_my_mac_enabled');
            $table->index('back_to_my_mac_relay_port');
            $table->index('beta');
            $table->index('bookmarks_enabled');
            $table->index('calendar_enabled');
            $table->index('cloud_photo_enabled');
            $table->index('cloud_photo_only_keep_thumbnail');
            $table->index('clouddesktop_declined_upgrade');
            $table->index('clouddesktop_desktop_enabled');
            $table->index('clouddesktop_documents_enabled');
            $table->index('clouddesktop_drive_enabled');
            $table->index('clouddesktop_first_sync_down_complete');
            $table->index('contacts_enabled');
            $table->index('display_name');
            $table->index('family_show_manage_family');
            $table->index('find_my_mac_enabled');
            $table->index('is_managed_apple_id');
            $table->index('keychain_sync_enabled');
            $table->index('logged_in');
            $table->index('mail_and_notes_dot_mac_mail_supported');
            $table->index('mail_and_notes_email_address');
            $table->index('mail_and_notes_enabled');
            $table->index('mail_and_notes_full_user_name');
            $table->index('mail_and_notes_username');
            $table->index('mobile_documents_enabled');
            $table->index('notes_enabled');
            $table->index('photo_stream_enabled');
            $table->index('prefpath');
            $table->index('primary_email_verified');
            $table->index('reminders_enabled');
            $table->index('shared_streams_enabled');
            $table->index('should_configure');
            $table->index('siri_enabled');
            $table->index('imessage_syncing_enabled');
            $table->index('imessage_currently_syncing');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('icloud');
    }
}
