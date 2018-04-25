<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DisplaysInfoAddExtendedColumns extends Migration
{
    private $tableName = 'displays';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('ui_resolution')->nullable();
            $table->string('current_resolution')->nullable();
            $table->string('color_depth')->nullable();
            $table->string('display_type')->nullable();
            $table->boolean('main_display')->nullable();
            $table->boolean('mirror')->nullable();
            $table->string('mirror_status')->nullable();
            $table->boolean('online')->nullable();
            $table->boolean('interlaced')->nullable();
            $table->boolean('rotation_supported')->nullable();
            $table->boolean('television')->nullable();
            $table->boolean('display_asleep')->nullable();
            $table->boolean('ambient_brightness')->nullable();
            $table->boolean('automatic_graphics_switching')->nullable();
            $table->boolean('retina')->nullable();
            $table->boolean('edr_enabled')->nullable();
            $table->float('edr_limit')->nullable();
            $table->boolean('edr_supported')->nullable();
            $table->string('connection_type')->nullable();
            $table->string('dp_dpcd_version')->nullable();
            $table->string('dp_current_bandwidth')->nullable();
            $table->integer('dp_current_lanes')->nullable();
            $table->string('dp_current_spread')->nullable();
            $table->boolean('dp_hdcp_capability')->nullable();
            $table->string('dp_max_bandwidth')->nullable();
            $table->integer('dp_max_lanes')->nullable();
            $table->string('dp_max_spread')->nullable();
            $table->boolean('virtual_device')->nullable();
            $table->string('dynamic_range')->nullable();
            $table->string('dp_adapter_firmware_version')->nullable();
            
            $table->index('ui_resolution');
            $table->index('current_resolution');
            $table->index('color_depth');
            $table->index('display_type');
            $table->index('main_display');
            $table->index('mirror');
            $table->index('mirror_status');
            $table->index('online');
            $table->index('interlaced');
            $table->index('rotation_supported');
            $table->index('television');
            $table->index('display_asleep');
            $table->index('ambient_brightness');
            $table->index('automatic_graphics_switching');
            $table->index('retina');
            $table->index('edr_enabled');
            $table->index('edr_limit');
            $table->index('edr_supported');
            $table->index('connection_type');
            $table->index('dp_dpcd_version');
            $table->index('dp_current_bandwidth');
            $table->index('dp_current_lanes');
            $table->index('dp_current_spread');
            $table->index('dp_hdcp_capability');
            $table->index('dp_max_bandwidth');
            $table->index('dp_max_lanes');
            $table->index('dp_max_spread');
            $table->index('virtual_device');
            $table->index('dynamic_range');
            $table->index('dp_adapter_firmware_version');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('ui_resolution');
            $table->dropColumn('color_depth');
            $table->dropColumn('display_type');
            $table->dropColumn('main_display');
            $table->dropColumn('mirror');
            $table->dropColumn('mirror_status');
            $table->dropColumn('online');
            $table->dropColumn('interlaced');
            $table->dropColumn('rotation_supported');
            $table->dropColumn('television');
            $table->dropColumn('display_asleep');
            $table->dropColumn('ambient_brightness');
            $table->dropColumn('automatic_graphics_switching');
            $table->dropColumn('retina');
            $table->dropColumn('edr_enabled');
            $table->dropColumn('edr_limit');
            $table->dropColumn('edr_supported');
            $table->dropColumn('connection_type');
            $table->dropColumn('dp_dpcd_version');
            $table->dropColumn('dp_current_bandwidth');
            $table->dropColumn('dp_current_lanes');
            $table->dropColumn('dp_current_spread');
            $table->dropColumn('dp_hdcp_capability');
            $table->dropColumn('dp_max_bandwidth');
            $table->dropColumn('dp_max_lanes');
            $table->dropColumn('dp_max_spread');
            $table->dropColumn('virtual_device');
            $table->dropColumn('dynamic_range');
            $table->dropColumn('dp_adapter_firmware_version');            
        });
    }
}
