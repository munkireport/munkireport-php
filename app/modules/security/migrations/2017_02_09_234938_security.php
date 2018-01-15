<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;


class Security extends Migration
{
    private $tableName = 'security';
    private $tableNameV2 = 'security_orig';

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

            $table->string('serial_number')->unique();
            $table->string('gatekeeper');
            $table->string('sip');
            $table->string('ssh_users');
            $table->string('ard_users');
            $table->string('firmwarepw');
            $table->string('firewall_state');
            $table->string('skel_state');

            $table->index('gatekeeper');
            $table->index('sip');
            $table->index('ssh_users');
            $table->index('ard_users');
            $table->index('firmwarepw');
            $table->index('firewall_state');
            $table->index('skel_state');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                gatekeeper,
                sip,
                ssh_users,
                ard_users,
                firmwarepw,
                firewall_state,
                ''
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
