<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class AddDsconfigadData extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'directoryservice';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('directoryservice');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('directoryservice', function (Blueprint $table) {
                $table->string('adforest')->nullable();
                $table->string('addomain')->nullable();
                $table->string('computeraccount')->nullable();
                $table->boolean('createmobileaccount')->nullable();
                $table->boolean('requireconfirmation')->nullable();
                $table->boolean('forcehomeinstartup')->nullable();
                $table->boolean('mounthomeassharepoint')->nullable();
                $table->boolean('usewindowsuncpathforhome')->nullable();
                $table->string('networkprotocoltobeused')->nullable();
                $table->string('defaultusershell')->nullable();
                $table->string('mappinguidtoattribute')->nullable();
                $table->string('mappingusergidtoattribute')->nullable();
                $table->string('mappinggroupgidtoattr')->nullable();
                $table->boolean('generatekerberosauth')->nullable();
                $table->string('preferreddomaincontroller')->nullable();
                $table->string('allowedadmingroups')->nullable();
                $table->boolean('authenticationfromanydomain')->nullable();
                $table->string('packetsigning')->nullable();
                $table->string('packetencryption')->nullable();
                $table->string('passwordchangeinterval')->nullable();
                $table->string('restrictdynamicdnsupdates')->nullable();
                $table->string('namespacemode')->nullable();
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('directoryservice');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'directoryservice',
                function (Blueprint $table) {
                    $table->dropColumn(
                        [
                            'adforest',
                            'addomain',
                            'computeraccount',
                            'createmobileaccount',
                            'requireconfirmation',
                            'forcehomeinstartup',
                            'mounthomeassharepoint',
                            'usewindowsuncpathforhome',
                            'networkprotocoltobeused',
                            'defaultusershell',
                            'mappinguidtoattribute',
                            'mappingusergidtoattribute',
                            'mappinggroupgidtoattr',
                            'generatekerberosauth',
                            'preferreddomaincontroller',
                            'allowedadmingroups',
                            'authenticationfromanydomain',
                            'packetsigning',
                            'packetencryption',
                            'passwordchangeinterval',
                            'restrictdynamicdnsupdates',
                            'namespacemode'
                        ]
                    );
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}