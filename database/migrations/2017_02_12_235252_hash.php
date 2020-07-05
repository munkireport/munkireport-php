<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class Hash extends Migration
{
    private $tableName = 'hash';
    private $tableNameV2 = 'hash_orig';

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
            $table->string('serial_number');
            $table->string('name', 50);
            $table->string('hash');
            $table->bigInteger('timestamp');
        });

        if ($migrateData) {
            Schema::unprepared("INSERT INTO
                $this->tableName
            SELECT
                id,
                serial,
                name,
                hash,
                timestamp
            FROM
                $this->tableNameV2");
            Schema::drop($this->tableNameV2);
        }

        // (Re)create indexes
        Schema::table($this->tableName, function (Blueprint $table) {
          $table->index(['serial_number']);
          $table->index(['serial_number', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
        if (Schema::hasTable($this->tableNameV2)) {
            Schema::rename($this->tableNameV2, $this->tableName);
        }
    }
}
