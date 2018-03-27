<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class NetworkShares extends Migration
{
    private $tableName = 'network_shares';
    private $tableNameV2 = 'network_shares_orig';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('name')->nullable();
            $table->string('mntfromname')->nullable();
            $table->string('fstypename')->nullable();
            $table->string('fsmtnonname')->nullable();
            $table->boolean('automounted')->nullable();
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                mntfromname,
                fstypename,
                fsmtnonname,
                automounted
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('name');
            $table->index('mntfromname');
            $table->index('fstypename');
            $table->index('fsmtnonname');
            $table->index('automounted');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
