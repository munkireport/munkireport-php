<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * If you have MunkiReport v2 installed and it hasn't yet run the php migrations, these migrations will run as a part
 * of the v3 upgrade.
 */
//class SecurityPhpMigrations extends Migration
//{
//    public function up() {
//        $capsule = new Capsule();
//
//
//
//
//
//        // Omitted: 004_security_add_indexes.php
//
//        // 005_security_add_firewall_state.php
//        if (!$capsule::schema()->hasColumn('security', 'firewall_state')) {
//            $capsule::schema()->table('security', function (Blueprint $table) {
//                $table->integer('firewall_state')->nullable();
//                $table->index('firewall_state', 'security_firewall_state');
//            });
//        }
//
//        // 006_security_add_skel_state.php
//        if (!$capsule::schema()->hasColumn('security', 'skel_state')) {
//            $capsule::schema()->table('security', function (Blueprint $table) {
//                $table->integer('skel_state')->nullable();
//            });
//        }
//    }
//
//    public function down() {
//        $capsule = new Capsule();
//        $capsule::schema()->table('security', function (Blueprint $table) {
//            $table->dropColumn('firewall_state');
//            $table->dropColumn('firmwarepw');
//            $table->dropColumn('ard_users');
//            $table->dropColumn('ssh_users');
//        });
//    }
//}