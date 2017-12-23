<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Directoryservice extends Migration
{
    public function up()
    {
        $capsule = new Capsule();

        if ($capsule::schema()->hasTable('directoryservice_v2')) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable('directoryservice')) {
            $capsule::schema()->rename('directoryservice', 'directoryservice_v2');
            $migrateData = true;
        }

        $capsule::schema()->create('directoryservice', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('which_directory_service')->nullable();
            $table->string('directory_service_comments')->nullable();
            $table->string('adforest')->nullable();
            $table->string('addomain')->nullable();
            $table->string('computeraccount')->nullable();
            $table->tinyInteger('createmobileaccount')->nullable();
            $table->tinyInteger('requireconfirmation')->nullable();
            $table->tinyInteger('forcehomeinstartup')->nullable();
            $table->tinyInteger('mounthomeassharepoint')->nullable();
            $table->tinyInteger('usewindowsuncpathforhome')->nullable();
            $table->string('networkprotocoltobeused')->nullable();
            $table->string('defaultusershell')->nullable();
            $table->string('mappinguidtoattribute')->nullable();
            $table->string('mappingusergidtoattribute')->nullable();
            $table->string('mappinggroupgidtoattr')->nullable();
            $table->boolean('generatekerberosauth')->nullable();
            $table->string('preferreddomaincontroller')->nullable();
            $table->string('allowedadmingroups')->nullable();
            $table->tinyInteger('authenticationfromanydomain')->nullable();
            $table->string('packetsigning')->nullable();
            $table->string('packetencryption')->nullable();
            $table->string('passwordchangeinterval')->nullable();
            $table->string('restrictdynamicdnsupdates')->nullable();
            $table->string('namespacemode')->nullable();

            $table->index('allowedadmingroups');
            $table->index('directory_service_comments');
            $table->index('which_directory_service');
        });

        if ($migrateData) {
            $capsule::select('INSERT INTO 
                directoryservice (serial_number, which_directory_service, directory_service_comments, adforest, addomain, computeraccount, createmobileaccount, requireconfirmation, forcehomeinstartup, mounthomeassharepoint, usewindowsuncpathforhome, networkprotocoltobeused, defaultusershell, mappinguidtoattribute, mappingusergidtoattribute, mappinggroupgidtoattr, generatekerberosauth, preferreddomaincontroller, allowedadmingroups, authenticationfromanydomain, packetsigning, packetencryption, passwordchangeinterval, restrictdynamicdnsupdates, namespacemode) 
            SELECT 
                serial_number,
                which_directory_service,
                directory_service_comments,
                adforest,
                addomain,
                computeraccount,
                createmobileaccount,
                requireconfirmation,
                forcehomeinstartup,
                mounthomeassharepoint,
                usewindowsuncpathforhome,
                networkprotocoltobeused,
                defaultusershell,
                mappinguidtoattribute,
                mappingusergidtoattribute,
                mappinggroupgidtoattr,
                generatekerberosauth,
                preferreddomaincontroller,
                allowedadmingroups,
                authenticationfromanydomain,
                packetsigning,
                packetencryption,
                passwordchangeinterval,
                restrictdynamicdnsupdates,
                namespacemode
            FROM
                directoryservice_v2');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('directoryservice');
        if ($capsule::schema()->hasTable('directoryservice_v2')) {
            $capsule::schema()->rename('directoryservice_v2', 'directoryservice');
        }
    }
}
