<?php
namespace App;

use Compatibility\Scopes\FilterScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use App\Scopes\CreatedSinceScope;
use munkireport\models\MRModel;
use Laravel\Scout\Searchable;

/**
 * Machine model represents information about the device that was not captured in reportdata
 *
 * @property int $id
 * @property string $serial_number
 * @property string $hostname
 * @property string $machine_model
 * @property string $machine_desc
 * @property string $img_url
 * @property string $cpu
 * @property string $current_processor_speed
 * @property string $cpu_arch
 * @property int $os_version
 * @property int $physical_memory
 * @property string $platform_UUID
 * @property int $number_processors
 * @property string $SMC_version_system
 * @property string $boot_rom_version
 * @property string $bus_speed
 * @property string $computer_name
 * @property string $l2_cache
 * @property string $machine_name
 * @property string $packages
 * @property string $buildversion
 *
 * @OA\Schema(
 *   schema="Machine",
 *   description="Basic information about a single client",
 *   type="object",
 *   @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="Machine Unique Identifier",
 *   ),
 *   @OA\Property(
 *      property="serial_number",
 *      type="string",
 *      description="Serial Number",
 *   ),
 *   @OA\Property(
 *      property="hostname",
 *      type="string",
 *      description="Local Hostname",
 *      example="computer.local",
 *   ),
 *   @OA\Property(
 *      property="machine_model",
 *      type="string",
 *      description="Model Identifier",
 *      example="iMac17,1",
 *   ),
 *   @OA\Property(
 *      property="machine_desc",
 *      type="string",
 *      description="Model Description",
 *   ),
 *   @OA\Property(
 *      property="img_url",
 *      type="string",
 *      format="url",
 *      description="Image URL",
 *   ),
 *   @OA\Property(
 *      property="cpu",
 *      type="string",
 *      description="CPU Information String",
 *   ),
 *   @OA\Property(
 *      property="current_processor_speed",
 *      type="string",
 *      description="Processor speed with unit of measurement",
 *      example="4 Ghz",
 *   ),
 *   @OA\Property(
 *      property="cpu_arch",
 *      type="string",
 *      description="CPU Architecture",
 *      example="x86_64",
 *   ),
 *   @OA\Property(
 *      property="os_version",
 *      type="integer",
 *      description="Operating System Version represented as an integer of 2 digit pairs",
 *      example="101201",
 *   ),
 *   @OA\Property(
 *      property="physical_memory",
 *      type="integer",
 *      description="Physical memory (in GB)",
 *      example="32",
 *   ),
 *   @OA\Property(
 *      property="platform_UUID",
 *      type="string",
 *      format="uuid",
 *      example="FC499AAF-884D-4FB2-87AE-81C9CBC6E559",
 *   ),
 *   @OA\Property(
 *      property="number_processors",
 *      type="integer",
 *      description="Number of (v)CPUs",
 *   ),
 *   @OA\Property(
 *      property="SMC_version_system",
 *      type="string",
 *      description="SMC version string",
 *   ),
 *   @OA\Property(
 *      property="boot_rom_version",
 *      type="string",
 *      description="Boot ROM version string",
 *   )
 * )
 */
class Machine extends MRModel
{
    use HasFactory;
    use Searchable;
    use FilterScope;

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

    /**
     * Override the route key name to allow Laravel model binding to work.
     */
    public function getRouteKeyName(): string
    {
        return 'serial_number';
    }

    //// RELATIONSHIPS

    /**
     * Get report data submitted by this machine
     */
    public function reportData(): HasOne
    {
        return $this->hasOne('App\ReportData', 'serial_number', 'serial_number');
    }

    /**
     * Get network information stored by the network module.
     *
     * Unfortunately, Machine requires this to work, because ClientsController.php:get_data() needs to join on
     * network.
     */
    public function network(): HasOne
    {
        return $this->hasOne('App\Network', 'serial_number', 'serial_number');
    }

    /**
     * Get a list of machine groups this machine is part of through the
     * `report_data` table.
     */
    public function machineGroups(): HasManyThrough {
        return $this->hasManyThrough(
            'App\MachineGroup', 'App\ReportData',
            'serial_number', 'id', 'serial_number'
        );
    }

    /**
     * Get a list of events generated by this machine.
     */
    public function events(): HasMany {
        return $this->hasMany('App\Event', 'serial_number', 'serial_number');
    }

    /**
     * Get a list of comments associated with this machine.
     * @return HasMany
     */
    public function comments(): HasMany {
        return $this->hasMany('App\Comment', 'serial_number', 'serial_number');
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

    //// SCOUT

    /**
     * Implements SCOUT interface for searchable models.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'serial_number' => $this->serial_number,
            'machine_model' => $this->machine_model,
            'hostname' => $this->hostname,
        ];
    }
}
