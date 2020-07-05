<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class MachineGroup extends Migration
{
    private $tableName = 'machine_group';
    private $tableNameV2 = 'machine_group_orig';

    public function up()
    {
        $migrateData = false;

        if (Schema::hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if (Schema::hasTable($this->tableName)) {
            Schema::rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('groupid')->nullable();
            $table->string('property');
            $table->string('value');
        });

        if ($migrateData) {
            Schema::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                groupid,
                property,
                value
            FROM
                $this->tableNameV2");
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
        if (Schema::hasTable($this->tableNameV2)) {
            Schema::rename($this->tableNameV2, $this->tableName);
        }
    }
}
