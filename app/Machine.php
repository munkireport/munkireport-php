<?php
namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MR\Scopes\CreatedSinceScope;
use munkireport\models\MRModel;

class Machine extends MRModel
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

    protected $casts = [
        'number_processors' => 'integer'
    ];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'serial_number';
    }

    //// RELATIONSHIPS

    /**
     * Get report data submitted by this machine
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reportData()
    {
        return $this->hasOne('App\ReportData', 'serial_number', 'serial_number');
    }

    /**
     * Get network information stored by the network module.
     *
     * Unfortunately, Machine requires this to work, because ClientsController.php:get_data() needs to join on
     * network.
     */
    public function network()
    {
        return $this->hasOne('App\Network', 'serial_number', 'serial_number');
    }

    /**
     * Get a list of machine groups this machine is part of through the
     * `report_data` table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function machineGroups() {
        return $this->hasManyThrough(
            'App\MachineGroup', 'App\ReportData',
            'serial_number', 'id', 'serial_number'
        );
    }

    //// SCOPES
    // Cannot use this while timestamps are disabled.
    // use CreatedSinceScope;
    use ProvidesHistogram;

    /**
     * Query scope for machines which have a duplicate computer name.
     */
    public function scopeDuplicate(Builder $query): Builder {
        return $query->groupBy('computer_name')
            ->having('COUNT(*)', '>', '1');
    }
}
