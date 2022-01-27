<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * V2 BusinessUnit (Not in Use).
 *
 * @see \Compatibility\BusinessUnit
 * @package App
 */
class BusinessUnit extends Model
{
    protected $fillable = [
        'name',
        'address',
        'link'
    ];

    //// RELATIONSHIPS

    /**
     * Retrieve a list of members of this business unit (managers and users).
     */
    public function users(): BelongsToMany {
        return $this->belongsToMany('App\User',
            'business_unit_user',
            'business_unit_id',
            'user_id');
    }

    /**
     * Retrieve a list of machine groups associated with this business unit
     */
    public function machineGroups(): BelongsToMany {
        return $this->belongsToMany('App\MachineGroup',
            'business_unit_machine_group',
            'business_unit_id',
            'machine_group_id'
        );
    }

    /**
     * Retrieve users who are managers of this business unit.
     */
    public function managers(): BelongsToMany {
        return $this->members()->wherePivot('role', 'manager');
    }

    /**
     * Retrieve users who are archivers in this business unit.
     */
    public function archivers(): BelongsToMany {
        return $this->members()->wherePivot('role', 'archiver');
    }

    /**
     * Retrieve users who are basic users in this business unit.
     */
    public function basicUsers(): BelongsToMany {
        return $this->members()->wherePivot('role', 'user');
    }
}
