<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CertificateBigintCertExpTime extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 2;
    public static $legacyTableName = 'certificate';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('certificate');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $capsule::schema()->table('certificate', function (Blueprint $table) {
                $table->bigInteger('cert_exp_time')->change();
            });

            $this->markLegacyMigrationRan();
        }
    }

    public function down() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('certificate');

        if ($legacyVersion !== null && $legacyVersion == static::$legacySchemaVersion) {
            $capsule::schema()->table(
                'certificate',
                function (Blueprint $table) {
                    $table->integer('cert_exp_time')->change();
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}