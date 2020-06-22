<?php

namespace MR;

use Illuminate\Database\Eloquent\Model;

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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members() {
        return $this->belongsToMany('MR\User');
    }

    /**
     * Retrieve users who are managers of this business unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers() {
        return $this->members()->wherePivot('role', 'manager');
    }

    /**
     * Retrieve users who are basic users in this business unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->members()->wherePivot('role', 'user');
    }

    /**
     * Retrieve the list of modules assigned to this business unit.
     * If there are none, we should treat this as all modules being enabled.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules() {
        return $this->belongstoMany('MR\Module');
    }
}
