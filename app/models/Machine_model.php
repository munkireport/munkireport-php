<?php
namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

class Machine_model extends Eloquent
{
    protected $table = 'machine';

    protected $fillable = [
        'serial_number',
        'hostname',
        'machine_model',
        'machine_desc',
        'img_url',
        'cpu',
        'current_processor_speed',
        'cpu_arch',
        'os_version',
        'physical_memory',
        'platform_UUID',
        'number_processors',
        'SMC_version_system',
        'boot_rom_version',
        'bus_speed',
        'computer_name',
        'l2_cache',
        'machine_name',
        'packages',
        'buildversion',
    ];

    public $timestamps = false;
}
