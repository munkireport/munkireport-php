<?php

namespace MR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Webpatser\Uuid\Uuid;

/**
 * MachineGroup model for v5 compatibility.
 */
class MachineGroup extends Model
{
    const PROP_NAME = 'name';
    const PROP_KEY = 'key';

    protected $table = 'machine_group';

    protected $fillable = [
        'groupid',
        'property',
        'value'
    ];

    protected $casts = [
        'groupid' => 'integer'
    ];

    /**
     * Create a machine group by passing the values for the `name` and `key` properties.
     *
     * @param int $machineGroupId The machine group id to use.
     * @param string $machineGroupName The name of the machine group.
     * @param null|string $machineGroupKey The machine group key you want to use, omit for auto generated key.
     * @return array A hash containing keys that correspond to the properties that were created. Each points to an
     *  Eloquent model.
     */
    public static function createWithParameters($machineGroupId, $machineGroupName, $machineGroupKey = null) {
        if ($machineGroupKey === null) {
            $machineGroupKey = Uuid::generate();
        }

        $nameRow = MachineGroup::create(
            ['property' => MachineGroup::PROP_NAME, 'value' => $machineGroupName, 'groupid' => $machineGroupId]);

        $keyRow = MachineGroup::create(
            ['property' => MachineGroup::PROP_KEY, 'value' => $machineGroupKey, 'groupid' => $machineGroupId]);

        return [
            'name' => $nameRow,
            'key' => $keyRow
        ];
    }

    /**
     * Convert a collection of MachineGroup properties and values to a hash structure.
     *
     * @param Collection $machineGroupRows The collection of rows to convert.
     * @return array
     */
    public static function hash(Collection $machineGroupRows) {
        return $machineGroupRows->reduce(function($carry, $item) {
            $carry[$item->property] = $item->value;
        }, []);
    }

    //// RELATIONSHIPS

    /**
     * Retrieve all report data rows associated with this machine group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportData()
    {
        return $this->hasMany('App\ReportData', 'machine_group', 'groupid');
    }

    /**
     * Retrieve all machine models associated with this machine group via ReportData.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function machines()
    {
        return $this->hasManyThrough('App\ReportData', 'App\Machine');
    }

    /**
     * Retrieve all BusinessUnits that contain this machine group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function businessUnits()
    {
        return $this->morphMany(
            'App\BusinessUnit',
            'businessUnitable',
            'property',
            'value',
            'groupid'
        );
    }

    //// SCOPES
}
