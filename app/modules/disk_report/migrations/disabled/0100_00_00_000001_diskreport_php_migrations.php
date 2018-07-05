<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * If you have MunkiReport v2 installed and it hasn't yet run the php migrations, these migrations will run as a part
 * of the v3 upgrade.
 */
class DiskreportPhpMigrations extends Migration
{
    public function up() {
        $capsule = new Capsule();
        if (!$capsule::schema()->hasColumn('diskreport', 'media_type')) {
            $capsule::schema()->table('diskreport', function (Blueprint $table) {
                $table->string('media_type');
            });
        }
    }

    public function down() {
        $capsule = new Capsule();
        $capsule::schema()->table('diskreport', function (Blueprint $table) {
           $table->dropColumn('media_type');
        });
    }
}