<?php

namespace MR;

use Illuminate\Database\Eloquent\Model;
use MR\Kiss\Contracts\LegacyMachineGroup;

class MachineGroup extends Model implements LegacyMachineGroup
{
    protected $fillable = [
        'name',
        'business_unit_id'
    ];

    //// RELATIONSHIPS

    /**
     * Retrieve the business unit that this machine group is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businessUnit() {
        return $this->belongsTo('MR\BusinessUnit');
    }

    /**
     * Get report data for machines in this machine group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportData() {
        return $this->hasMany('MR\ReportData', 'machine_group');
    }

    /**
     * Get a list of machines associated with this machine group through
     * the report data table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function machines() {
        return $this->hasManyThrough(
            'MR\Machine', 'MR\ReportData',
            'machine_group', 'serial_number'
        );
    }

    //// LegacyMachineGroup for Machine_group

    public function get_max_groupid()
    {
        // TODO: Implement get_max_groupid() method.
    }

    public function get_group_ids()
    {
        // TODO: Implement get_group_ids() method.
    }
}
