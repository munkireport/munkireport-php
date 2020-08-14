<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function members(): BelongsToMany {
        return $this->belongsToMany('App\User');
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
    public function users(): BelongsToMany {
        return $this->members()->wherePivot('role', 'user');
    }

    /**
     * Retrieve the list of modules assigned to this business unit.
     * If there are none, we should treat this as all modules being enabled.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
//    public function modules() {
//        return $this->belongstoMany('Mr\Module');
//    }
}
