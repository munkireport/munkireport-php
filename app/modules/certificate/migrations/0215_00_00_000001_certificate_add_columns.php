<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CertificateAddColumns extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;
    public static $legacyTableName = 'certificate';

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('certificate');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            $cols = $capsule::schema()->getColumnListing('certificate');

            $capsule::schema()->table('certificate', function (Blueprint $table) use ($cols) {
                !in_array('issuer', $cols) && $table->string('issuer')->nullable();
                !in_array('cert_location', $cols) && $table->string('cert_location')->nullable();

                $table->index('cert_path', 'certificate_cert_path');
                $table->index('cert_cn', 'certificate_cert_cn');
                $table->index('issuer', 'certificate_issuer');
                $table->index('cert_location', 'certificate_cert_location');
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
                    $table->dropIndex([
                        'certificate_cert_location',
                        'certificate_issuer',
                        'certificate_cert_cn',
                        'certificate_cert_path'
                    ]);
                }
            );

            $this->markLegacyRollbackRan();
        }
    }
}