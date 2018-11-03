<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * If you have MunkiReport v2 installed and it hasn't yet run the php migrations, these migrations will run as a part
 * of the v3 upgrade.
 *
 * This migration replaces `phpmigrations/001_certificate_add_columns.php`.
 */
class CertificatePhpMigrations extends Migration
{
    public function up() {
        $capsule = new Capsule();
        if (!$capsule::schema()->hasColumn('certificate', 'issuer')) {
            $capsule::schema()->table('certificate', function (Blueprint $table) {
                $table->string('issuer');
                $table->index('issuer', 'certificate_issuer');
            });
        }

        if (!$capsule::schema()->hasColumn('certificate', 'cert_location')) {
            $capsule::schema()->table('certificate', function (Blueprint $table) {
                $table->string('cert_location');
                $table->index('cert_location', 'certificate_cert_location');
            });
        }

        // 002_certificate_bigint_cert_exp_time.php
        $capsule::schema()->table('certificate', function (Blueprint $table) {
            $table->bigInteger('cert_exp_time')->change();
        });
    }

    public function down() {
        $capsule = new Capsule();
        $capsule::schema()->table('certificate', function (Blueprint $table) {
           $table->dropColumn('issuer');
           $table->dropColumn('cert_location');
        });
    }
}