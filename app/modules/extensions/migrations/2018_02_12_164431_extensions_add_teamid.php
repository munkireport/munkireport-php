<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ExtensionsAddTeamid extends Migration
{
    private $tableName = 'extensions';
    private $tableNameOld = 'extensions_old';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        // Check if old table exists from failed migration
        if ($capsule::schema()->hasTable($this->tableNameOld)) {
            // Migration already failed before, but didn't finish
            throw new Exception("previous failed migration exists");
        }

        // Rename table
        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameOld);
            $migrateData = true;
        }

        // Make new table with new column name
        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('name')->nullable();
            $table->string('bundle_id')->nullable();
            $table->string('version')->nullable();
            $table->string('path')->nullable();
            $table->string('developer')->nullable();
            $table->string('executable')->nullable();
        });

        // Copy over data from old table, if it exists
        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                bundle_id,
                version,
                path,
                codesign,
                executable
            FROM
                $this->tableNameOld");
            // Drop old table
            $capsule::schema()->drop($this->tableNameOld);
        }
        
        // Add new column
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('teamid')->after('developer')->nullable();
        });

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('serial_number');
            $table->index('name');
            $table->index('bundle_id');
            $table->index('version');
            $table->index('path');
            $table->index('developer');
            $table->index('teamid');
            $table->index('executable');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $migrateData = false;
        
        // Check if old table exists from failed migration
        if ($capsule::schema()->hasTable($this->tableNameOld)) {
            // Migration already failed before, but didn't finish
            throw new Exception("previous failed migration exists");
        }

        // Rename table
        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameOld);
            $migrateData = true;
        }

        // Make new table with new column name
        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('name')->nullable();
            $table->string('bundle_id')->nullable();
            $table->string('version')->nullable();
            $table->string('path')->nullable();
            $table->string('codesign')->nullable();
            $table->string('executable')->nullable();
        });

        // Copy over data from old table, if it exists
        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                bundle_id,
                version,
                path,
                developer,
                executable
            FROM
                $this->tableNameOld");
            // Drop old table
            $capsule::schema()->drop($this->tableNameOld);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('serial_number');
            $table->index('name');
            $table->index('bundle_id');
            $table->index('version');
            $table->index('path');
            $table->index('codesign');
            $table->index('executable');
        });
    }
}

