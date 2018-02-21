<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Fonts extends Migration
{
    private $tableName = 'fonts';
    private $tableNameV2 = 'fonts_orig';

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

            $table->string('name');
            $table->boolean('enabled');
            $table->string('type_name');
            $table->string('fullname');
            $table->boolean('type_enabled');
            $table->boolean('valid');
            $table->boolean('duplicate');
            $table->text('path');
            $table->string('type');
            $table->string('family');
            $table->string('style');
            $table->string('version');
            $table->boolean('embeddable');
            $table->boolean('outline');
            $table->string('unique_id');
            $table->text('copyright');
            $table->boolean('copy_protected');
            $table->text('description');
            $table->text('vendor');
            $table->text('designer');
            $table->text('trademark');
            
            $table->index('name');
            $table->index('type');
            $table->index('type_name');
            $table->index('family');
            $table->index('fullname');
            $table->index('style');
            $table->index('unique_id');
            $table->index('version');
            $table->index('enabled');
            $table->index('copy_protected');
            $table->index('duplicate');
            $table->index('embeddable');
            $table->index('type_enabled');
            $table->index('outline');
            $table->index('valid');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                enabled,
                type_name,
                fullname,
                type_enabled,
                valid,
                duplicate,
                path,
                type,
                family,
                style,
                version,
                embeddable,
                outline,
                unique_id,
                copyright,
                copy_protected,
                description,
                vendor,
                designer,
                trademark
            FROM
                $this->tableNameV2");
        }
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
