<?php

use munkireport\lib\Schema;


// Fix indexes for MySQL that were not created when migrating

class Migration_disk_report_add_media_type extends Model
{

    /**
     * Migrate up
     *
     * Migrates this table to the current version
     *
     **/
    public function up()
    {
        Schema::table('diskreport', function ($table)
        {
            $table->string('media_type');
            $table->index('media_type');
        });

    }// End function up()

    /**
     * Migrate down
     *
     * Migrates this table to the previous version
     *
     **/
    public function down()
    {
        // There is no down() as this is a bugfix and up() is idempotent
    }
}
