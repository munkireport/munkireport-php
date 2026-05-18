<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class NoserialCleanOne extends Migration
{
    public function up()
    {
        $capsule = new Capsule();

        // Try getting the table names for MySQL
        try {
            $tables = $capsule::select('SHOW TABLES');
        } catch (Exception $e) {
            // If it fails, get them for SQLite
            $tables = $capsule::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
        }

        // Loop through all the tables
        foreach ($tables as $table) {

            // Process each table
            foreach ($table as $key => $value) {

                // Get all columns in table
                $columns =  $capsule::connection()->getSchemaBuilder()->getColumnListing($value);

                // If serial_number columns exists
                if (in_array('serial_number', $columns)){
                    try {
                        // Remove the NOSERIAL rows
                        $capsule::unprepared("DELETE FROM ".$value." WHERE `serial_number` = 'NOSERIAL'");
                    } catch (Exception $e) {
                        // Print warning on what failed
                        print_r("<br> Failed to remove NOSERIAL from ".$value);
                    }
                }
            }
        }
    }

    public function down()
    {
        // No going back
    }
}